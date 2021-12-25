
<!DOCTYPE HTML>
<html>
<head>
    <title>Flask-SocketIO Test</title>

	
    <audio id="buzzer" src="./static/audio/beep.mp3" type="audio/mp3"></audio>

    <script type="text/javascript" src="./static/jquery/jquery-3.1.1.min.js"></script>
    <script type="text/javascript" src="./static/socket.io/1.3.5/socket.io.min.js"></script>
    <link rel="stylesheet" href="./static/bootstrap-3.3.7/css/bootstrap.min.css"></link>
    <script type="text/javascript" src=".//static/bootstrap-3.3.7/js/bootstrap.min.js"></script>
    <script type="text/javascript" charset="utf-8">

        get_version = undefined;
        get_timestamp = undefined;
        get_settings = undefined;
        reset = undefined;

        $(document).ready(function() {
            var buzzer = $('#buzzer')[0];

            var socket = io.connect('//192.168.42.1:5000');

            var num_nodes = 0;
            var start_timestamp = [];
            var last_lap_timestamp = [];

            function map_range(in_min, in_max, out_min, out_max, value) {
              return (value - in_min) * (out_max - out_min) / (in_max - in_min) + out_min;
            }

            function normalize_rssi(value) {
                return map_range(0, 300, 0, 100, value);
            }

            function append_to_log(text) {
                $('#log').prepend('<br>' + $('<div/>').text(text).html());
            }

            socket.on('connect', function() {
                append_to_log('connected!');
            });

            socket.on('heartbeat', function(msg) {
                // append_to_log('on heartbeat ' + JSON.stringify(msg));
                for (i=0; i<msg.current_rssi.length; i++) {
                    $('#current_rssi_' + i).html(msg.current_rssi[i]);
                    $('#current_rssi_bar_' + i).css("width", normalize_rssi(msg.current_rssi[i])+'%');
                }
            });

            socket.on('hardware_log', function(msg) {
                append_to_log('hardware log: ' + msg);
            });

            get_version = function(event) {
                socket.emit('get_version', function(msg) {
                    append_to_log('get_version returned ' + JSON.stringify(msg));
                });
                return false;
            }

            get_timestamp = function(event) {
                socket.emit('get_timestamp', function(msg) {
                    append_to_log('get_timestamp returned ' + JSON.stringify(msg));

                    last_lap_timestamp = [];
                    start_timestamp = [];
                    for (i=0; i<num_nodes; i++) {
                        last_lap_timestamp.push(msg.timestamp);
                        start_timestamp.push(msg.timestamp);
                    }
                });
                return false;
            }

            get_settings = function(event) {
                socket.emit('get_settings', function(msg) {
                    append_to_log('get_settings returned ' + JSON.stringify(msg));
                });
                return false;
            }

            reset = function() {
                for (i=0; i<num_nodes; i++) {
                    $('#lap_table_' + i).empty();
                    $('#peak_rssi_' + i).html(0);
                    $('#peak_rssi_bar_' + i).css("width", normalize_rssi(0)+'%');
                    $('#trigger_rssi_' + i).html(0);
                    $('#trigger_rssi_bar_' + i).css("width", normalize_rssi(0)+'%');
                }
                socket.emit('reset_auto_calibration', {node: -1});
                get_timestamp();
            };

            function set_frequency(index) {
                return function(event) {
                    var data = {
                        node: index,
                        frequency: parseInt($('#frequency_val_' +index).val())
                    };
                    socket.emit('set_frequency', data);
                    return false;
                }
            }

            function simulate_pass(index) {
                return function(event) {
                    var data = {
                        node: index
                    };
                    socket.emit('simulate_pass', data);
                    return false;
                }
            }

            $('form#set_calibration_threshold').submit(function() {
                var data = {
                    calibration_threshold: parseInt($('#calibration_threshold_value').val())
                }
                socket.emit('set_calibration_threshold', data);
                return false;
            });

            $('form#set_calibration_offset').submit(function() {
                var data = {
                    calibration_offset: parseInt($('#calibration_offset_value').val())
                }
                socket.emit('set_calibration_offset', data);
                return false;
            });

            $('form#set_trigger_threshold').submit(function() {
                var data = {
                    trigger_threshold: parseInt($('#trigger_threshold_value').val())
                }
                socket.emit('set_trigger_threshold', data);
                return false;
            });

            $('form#set_filter_ratio').submit(function() {
                var data = {
                    filter_ratio: parseInt($('#filter_ratio_value').val())
                }
                socket.emit('set_filter_ratio', data);
                return false;
            });

            socket.emit('get_settings', function(msg) {
                const Node = ({frequency, current_rssi, trigger_rssi}, index) => `
                    <div class='col-md-2'>
                        <div class='panel panel-default'>
                            <div class="panel-heading">
                                <h3 class="panel-title">Node ${index}</h3>
                            </div>
                            <div class="panel-body">
                            <form id="set_frequency_${index}" method="POST" action='#'>
                                <div class="form-group">
                                    Frequency: <span id='frequency_${index}'>${frequency}</span>
                                    <div class="input-group input-group-sm">
                                        <input class="form-control" type="text" name="frequency_val_${index}" id="frequency_val_${index}" value='${frequency}'>
                                        <span class="input-group-btn">
                                            <input class="btn btn-default" type="submit" value="Set">
                                        </span>
                                    </div>
                                </div>
                            </form>
                            <div class="progress">
                              <div id="peak_rssi_bar_${index}" class="progress-bar progress-bar-info" role="progressbar" style="width: 0%">
                                <span id='peak_rssi_${index}'>0</span>
                              </div>
                            </div>
                            <div class="progress">
                              <div id="trigger_rssi_bar_${index}" class="progress-bar progress-bar-danger" role="progressbar" style="width: 0%">
                                <span id='trigger_rssi_${index}'>0</span>
                              </div>
                            </div>
                            <div class="progress">
                              <div id="current_rssi_bar_${index}" class="progress-bar progress-bar-success" role="progressbar" style="width: 70%">
                                <span id='current_rssi_${index}'>${current_rssi}</span>
                              </div>
                            </div>
                            <form id="simulate_pass_${index}" method="POST" action="#">
                                <input class="btn btn-default btn-sm" type="submit" value="Simulate Pass">
                            </form>
                            </div>
                            <table class="table">
                                <tbody id="lap_table_${index}">
                                </tbody>
                            </table>
                        </div>
                    </div>
                `;

                num_nodes = msg.nodes.length;

                $('#nodes').html(msg.nodes.map(Node).join(''));

                for (i=0; i<msg.nodes.length; i++) {
                    $('form#set_frequency_' + i).submit(set_frequency(i));
                    $('form#simulate_pass_' + i).submit(simulate_pass(i));
                }

                $('#calibration_threshold_value').val(msg.calibration_threshold);
                $('#calibration_offset_value').val(msg.calibration_offset);
                $('#trigger_threshold_value').val(msg.trigger_threshold);
                $('#filter_ratio_value').val(msg.filter_ratio);

                append_to_log('get_settings returned ' + JSON.stringify(msg));

                get_timestamp();
            });

            socket.on('frequency_set', function(msg) {
                $('#frequency_' + msg.node).html(msg.frequency);
                append_to_log('on frequency_set ' + JSON.stringify(msg));
            });

            socket.on('trigger_rssi_set', function(msg) {
                $('#trigger_' + msg.node).html(msg.trigger_rssi);
                append_to_log('on trigger_rssi_set ' + JSON.stringify(msg));
            });

            socket.on('calibration_threshold_set', function(msg) {
                $('#calibration_threshold_value').val(msg.calibration_threshold);
                append_to_log('on calibration_threshold_set ' + JSON.stringify(msg));
            });

            socket.on('calibration_offset_set', function(msg) {
                $('#calibration_offset_value').val(msg.calibration_offset);
                append_to_log('on calibration_offset_set ' + JSON.stringify(msg));
            });

            socket.on('trigger_threshold_set', function(msg) {
                $('#trigger_threshold_value').val(msg.trigger_threshold);
                append_to_log('on trigger_threshold_set ' + JSON.stringify(msg));
            });

            socket.on('filter_ratio_set', function(msg) {
                $('#filter_ratio_value').val(msg.filter_ratio);
                append_to_log('on filter_ratio_set ' + JSON.stringify(msg));
            });

            function ms_to_time(s) {
              // Pad to 2 or 3 digits, default is 2
              function pad(n, z) {
                z = z || 2;
                return ('00' + n).slice(-z);
              }

              var ms = s % 1000;
              s = (s - ms) / 1000;
              var secs = s % 60;
              s = (s - secs) / 60;
              var mins = s % 60;

              return pad(mins) + ':' + pad(secs) + '.' + pad(ms, 3);
            }

            socket.on('pass_record', function(msg) {
                //buzzer.play();
		
                var lap_table = $('#lap_table_' + msg.node);
                const Lap = (index, rssi, time_lap, time_total) => `
                    <tr>
                        <td>${index}</td>
                        <!--td>${rssi}</td-->
                        <td><p class="text-right">${time_lap}</p></td>
                        <td><p class="text-right">${time_total}</p></td>
                    </tr>
                `;
				

		
                var time_lap = msg.timestamp - last_lap_timestamp[msg.node];
                var time_total = msg.timestamp - start_timestamp[msg.node];
				var headid=document.getElementById("set_head_id").value;
                last_lap_timestamp[msg.node] = msg.timestamp;
                lap_table.append(Lap(lap_table[0].children.length, msg.peak_rssi, ms_to_time(time_lap), ms_to_time(time_total)));
                append_to_log('on pass_record ' + JSON.stringify(msg));
						var xhttp = new XMLHttpRequest();
				  xhttp.open("GET", "db.php?timestamp="+time_lap+"&node="+msg.node+"&lap="+(lap_table[0].children.length-1)+"&head="+headid, true);
				xhttp.send()
                $('#peak_rssi_' + msg.node).html(msg.peak_rssi);
                $('#peak_rssi_bar_' + msg.node).css("width", normalize_rssi(msg.peak_rssi)+'%');
                $('#trigger_rssi_' + msg.node).html(msg.trigger_rssi);
                $('#trigger_rssi_bar_' + msg.node).css("width", normalize_rssi(msg.trigger_rssi)+'%');
            });
        });
    </script>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-6">
                <div class='panel panel-default'>
                    <div class="panel-heading">
                        <h3 class="panel-title">Global Settings</h3>
                    </div>
                    <div class="panel-body">

                        <form id="set_calibration_threshold" method="POST" action='#'>
                            <div class="form-group">
                                Calibration Threshold
                                <div class="input-group input-group-sm">
                                    <input class="form-control" type="text" name="calibration_threshold_value" id="calibration_threshold_value" value='0'>
                                    <span class="input-group-btn">
                                        <input class="btn btn-default" type="submit" value="Set">
                                    </span>
                                </div>
                            </div>
                        </form>

                        <form id="set_calibration_offset" method="POST" action='#'>
                            <div class="form-group">
                                Calibration Offset
                                <div class="input-group input-group-sm">
                                    <input class="form-control" type="text" name="calibration_offset_value" id="calibration_offset_value" value='0'>
                                    <span class="input-group-btn">
                                        <input class="btn btn-default" type="submit" value="Set">
                                    </span>
                                </div>
                            </div>
                        </form>

                        <form id="set_trigger_threshold" method="POST" action='#'>
                            <div class="form-group">
                                Trigger Threshold
                                <div class="input-group input-group-sm">
                                    <input class="form-control" type="text" name="trigger_threshold_value" id="trigger_threshold_value" value='0'>
                                    <span class="input-group-btn">
                                        <input class="btn btn-default" type="submit" value="Set">
                                    </span>
                                </div>
                            </div>
                        </form>

                        <form id="set_filter_ratio" method="POST" action='#'>
                            <div class="form-group">
                                Filter Ratio
                                <div class="input-group input-group-sm">
                                    <input class="form-control" type="text" name="filter_ratio_value" id="filter_ratio_value" value='0'>
                                    <span class="input-group-btn">
                                        <input class="btn btn-default" type="submit" value="Set">
                                    </span>
                                </div>
                            </div>
                        </form>
						
						
                        <form id="set_head" method="POST" action='#'>
                            <div class="form-group">
                                Head ID
                                <div class="input-group input-group-sm">
                                    <input class="form-control" type="text" name="set_head_id" id="set_head_id" value='0'>
                                    <span class="input-group-btn">
                                     
                                    </span>
                                </div>
                            </div>
                        </form>

                        <button class="btn" onclick="get_version()">Get Version</button>
                        <button class="btn" onclick="get_timestamp()">Get Timestamp</button>
                        <button class="btn" onclick="get_settings()">Get Settings</button>
                        <button class="btn btn-space btn-danger" onclick="reset()">Reset</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="nodes"></div>
        <h3>Receive:</h3>
    <div id="log"></div>
</div>
</body>
</html>
