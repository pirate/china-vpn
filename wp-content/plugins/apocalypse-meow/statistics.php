<?php
//----------------------------------------------------------------------
//  Apocalypse Meow log-in statistics
//----------------------------------------------------------------------
//display fun stats about the log-in data we've collected
//
// @since 1.3.3



//--------------------------------------------------
//Check permissions

//let's make sure this page is being accessed through WP
if (!function_exists('current_user_can'))
	die('Sorry');
//and let's make sure the current user has sufficient permissions
elseif(!current_user_can('manage_options'))
	wp_die(__('You do not have sufficient permissions to access this page.'));



//--------------------------------------------------
//Compile data!

//we need $wpdb
global $wpdb;

//first, let's find the date range for the data
$dates = array();
//if there is no min_date, there are no records, no stats
if(false === ($min_date = $wpdb->get_var("SELECT MIN(DATE(FROM_UNIXTIME(`date`))) FROM `{$wpdb->prefix}meow_log`")) || !intval($wpdb->get_var("SELECT COUNT(*) FROM `{$wpdb->prefix}meow_log`")))
{
	echo '<div class="wrap">' . meow_get_header() . '<div class="error fade"><p>There is no log-in history.</p></div></div>';
	exit;
}
$max_date = $wpdb->get_var("SELECT CURDATE()");
for($x=0; date("Y-m-d", strtotime("+$x days", strtotime($min_date))) <= $max_date; $x++)
	$dates[date("Y-m-d", strtotime("+$x days", strtotime($min_date)))] = array();

//we want daily counts grouped by status
$data_date_ip = array(0=>$dates, 1=>$dates);
$data_date_ua = array(0=>$dates, 1=>$dates);
$data_date_user = array(0=>$dates, 1=>$dates);
$data_date_gross = array(0=>$dates, 1=>$dates);
//and we want overall data too
$data_ip = array(0=>array(), 1=>array());
$data_ua = array(0=>array(), 1=>array());
$data_user = array(0=>array(), 1=>array());
//and some username stats
$data_users = array(0=>array(), 1=>array());
//and some totals
$total_rows = 0;
$total_by_status = array(0=>0, 1=>0);
$total_failed = 0;
$total_banned = 0;
$total_ip = 0;
$total_ua = 0;
$total_user = 0;
$total_days = count(array_keys($dates));
//apocalypse stats
$total_apocalypse = (int) $wpdb->get_var("SELECT SUM(`count`) FROM `{$wpdb->prefix}meow_log_banned`");
$total_apocalypse_ip = (int) $wpdb->get_var("SELECT COUNT(DISTINCT(`ip`)) FROM `{$wpdb->prefix}meow_log_banned`");

//ok, finally pull some data
$dbResult = $wpdb->get_results("SELECT `id`, `ip`, DATE(FROM_UNIXTIME(`date`)) AS `date`, `success`, `ua`, `username` FROM `{$wpdb->prefix}meow_log` ORDER BY `date` ASC", ARRAY_A);
if($wpdb->num_rows > 0)
{
	$total_rows = $wpdb->num_rows;
	foreach($dbResult AS $Row)
	{
		$Row['success'] = (int) $Row['success'];

		//ip
		if(filter_var($Row['ip'], FILTER_VALIDATE_IP))
		{
			$data_date_ip[$Row['success']][$Row['date']][] = $Row['ip'];
			$data_ip[$Row['success']][] = $Row['ip'];
		}

		//user agent
		if(strlen($Row['ua']))
		{
			$data_date_ua[$Row['success']][$Row['date']][] = $Row['ua'];
			$data_ua[$Row['success']][] = $Row['ua'];
		}

		//username
		if(strlen($Row['username']))
		{
			$data_date_user[$Row['success']][$Row['date']][] = $Row['username'];
			$data_user[$Row['success']][] = $Row['username'];
			if(!isset($data_users[$Row['success']][$Row['username']]))
				$data_users[$Row['success']][$Row['username']] = 1;
			else
				$data_users[$Row['success']][$Row['username']]++;
		}

		//total
		$data_date_gross[$Row['success']][$Row['date']][] = (int) $Row['id'];
		$total_by_status[$Row['success']]++;
	}

	//clean up data
	for($x=0; $x<=1; $x++)
	{
		foreach(array('data_date_ip','data_date_user','data_date_ua') AS $var)
		{
			foreach(${$var}[$x] AS $k=>$v)
				${$var}[$x][$k] = array_unique($v);
		}

		foreach(array('data_ip','data_ua','data_user') AS $var)
			${$var}[$x] = array_unique(${$var}[$x]);
	}

	arsort($data_users[0]);
	arsort($data_users[1]);

	//count totals
	$total_ip = count(array_unique(array_merge($data_ip[0], $data_ip[1])));
	$total_ua = count(array_unique(array_merge($data_ua[0], $data_ua[1])));
	$total_user = count(array_unique(array_merge($data_user[0], $data_user[1])));
}

?>
<style type="text/css">
	.meow-graph {
		height: 300px;
		width: 100%;
	}
	.meow-graph-container-half {
		float: left;
		width: 48%;
		min-width: 250px;
		margin-right: 4%;
		margin-top: 10px;
	}
	.meow-graph-container-third {
		float: left;
		width: 30%;
		margin-right: 5%;
		min-width: 250px;
		margin-top: 10px;
	}
	.meow-clear {
		float: none;
		clear: left;
		width: 100%;
		height: 1px;
		overflow: hidden;
	}
	.meow-label {
		font-weight: bold;
		margin-right: 5px;
	}
	.meow-pie {
		width: 257px;
		height: 257px;
	}
</style>
<div class="wrap">

	<?php echo meow_get_header(); ?>

	<div class="metabox-holder has-right-sidebar">

		<div class="inner-sidebar">
			<!--start successful logins-->
			<div class="postbox">
				<h3 class="hndle">Successful Log-ins (<?php echo round(100 * $total_by_status[1] / ($total_by_status[0] + $total_by_status[1]), 1); ?>%)</h3>
				<div class="inside">
					<ul>
						<li>
							<span class="meow-label">Top users:</span>
							<div id="meow-pie-users-1" class="meow-pie" data-pie-content="users_1"></div>
						</li>
						<li><span class="meow-label">Total:</span><?php echo $total_by_status[1]; ?></li>
						<li><span class="meow-label">Daily average:</span><?php echo round($total_by_status[1]/$total_days,1); ?></li>
						<li><span class="meow-label">Unique IPs:</span><?php echo count($data_ip[1]); ?></li>
						<?php
						if(meow_get_option('meow_store_ua'))
							echo '<li><span class="meow-label">Unique browsers:</span>' . count($data_ua[1]) . '</li>';
						?>
					</ul>
				</div>
			</div>
			<!--end successful logins-->

			<!--start failed logins-->
			<div class="postbox">
				<h3 class="hndle">Unsuccessful Log-ins (<?php echo round(100 * $total_by_status[0] / ($total_by_status[0] + $total_by_status[1]), 1); ?>%)</h3>
				<div class="inside">
					<ul>
						<li>
							<span class="meow-label">Top users:</span>
							<div id="meow-pie-users-0" class="meow-pie" data-pie-content="users_0"></div>
						</li>
						<li><span class="meow-label">Total:</span><?php echo $total_by_status[0]; ?></li>
						<li><span class="meow-label">Daily average:</span><?php echo round($total_by_status[0]/$total_days,1); ?></li>
						<li><span class="meow-label">Unique IPs:</span><?php echo count($data_ip[0]); ?></li>
						<li><span class="meow-label">IPs banned:</span><?php echo $total_apocalypse_ip; ?></li>
						<li><span class="meow-label">Apocalypses served:</span><?php echo $total_apocalypse; ?></li>
						<li><span class="meow-label">Apocalypses per day:</span><?php echo round($total_apocalypse/$total_days,1); ?></li>
						<?php
						if(meow_get_option('meow_store_ua'))
							echo '<li><span class="meow-label">Unique browsers:</span>' . count($data_ua[0]) . '</li>';
						?>
					</ul>
				</div>
			</div>
			<!--end failed logins-->
		</div>

		<div id="post-body-content" class="has-sidebar">
			<div class="has-sidebar-content">

				<!--start log-in history graph-->
				<div class="postbox">
					<h3 class="hndle">Daily log-in attempts, separated by result</h3>
					<div class="inside">

						<p class="description">FYI: These graphs support panning (click and drag) and zooming (double-click or use your mouse's scrollwheel) if you need a better view.</p>

						<div class="meow-graph-container-<?php echo (meow_get_option('meow_store_ua') ? 'third' : 'half'); ?>">
							<div id="meow-graph-gross" class="meow-graph meow-graph-date" data-graph-content="gross"></div>
							<p>Every log-in attempt. Keep in mind, a single robot can try thousands of log-ins a minute.</p>
						</div>

						<?php if(meow_get_option('meow_store_ua')){  ?>
						<div class="meow-graph-container-third">
							<div id="meow-graph-ua" class="meow-graph meow-graph-date" data-graph-content="ua"></div>
							<p>Unique web browsers attempting log-in.  This data is often forged, but might still be useful.</p>
						</div>
						<?php } ?>

						<div class="meow-graph-container-<?php echo (meow_get_option('meow_store_ua') ? 'third' : 'half'); ?>" style="margin-right: 0;">
							<div id="meow-graph-ip" class="meow-graph meow-graph-date" data-graph-content="ip"></div>
							<p>Unique individuals (IP addresses) attempting log-in.  This is probably the most useful metric for determining how big a target you are for nogoodniks.</p>
						</div>

						<div class="meow-clear">&nbsp;</div>
					</div>
				</div>
				<!-- end log-in history graph -->

				<div class="postbox">
					<h3 class="hndle">Data Retention</h3>
					<div class="inside">
						<p><?php
	//are logs cleaned every so often?
	$meow_clean_database = meow_get_option('meow_clean_database');
	$meow_data_expiration = meow_get_option('meow_data_expiration');
	if($meow_clean_database)
		echo "Records older than $meow_data_expiration days are automatically purged from the database.";
	else
		echo "Log-in data is currently retained forever, which is a long time.  If you find these stats getting a touch unruly, you can have the system automatically purge records after a certain amount of time.";
?>  Visit the <a href="<?php echo esc_url(admin_url('options-general.php?page=meow-settings')); ?>" title="Apocalypse Meow settings">settings page</a> to change this behavior.</p>
					</div>
				</div>

			</div><!-- /.has-sidebar-content -->
		</div><!-- /.has-sidebar -->

	</div>

</div>

<script type="text/javascript">

var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
var graph_data = new Array();
var pie_data = new Array();

jQuery(function () {

<?php
	//convert some of this PHP data to Javascript for graphing enjoyment!
	//first we have the general log-in counts graphs
	foreach(array('gross','ua','ip') AS $type)
	{
		echo "\ngraph_data['$type'] = [";
		for($x=0; $x<=1; $x++)
		{
			echo "[";
			foreach(${"data_date_$type"}[$x] AS $k=>$v)
			{
				if(count($v))
					echo "[" . (strtotime($k) * 1000) . "," . count($v) . "],";
			}
			echo "],";
		}
		echo "];\n";
	}

	//and now the username pie charts
	foreach($data_users AS $status=>$v)
	{
		$num = array_sum($v);
		$tmp = 0;
		echo "\npie_data['users_$status'] = [";
		foreach($v AS $k2=>$v2)
		{
			$tmp++;
			if($tmp > 10)
				break;
			$label = esc_js($k2);
			if(strlen($k2) > 20)
				$label = trim(substr($k2,0,17) . '...');
			$percent = round(100 * $v2/$num);
			echo "{label: '$label $percent%', data: $v2},";
		}
		echo "];\n";
	}
?>

	//generate plot graphs
	jQuery('.meow-graph-date').each(function(k,v){
		var graph = jQuery(this);
		jQuery.plot(graph, [
				{ label: "Successful",  data: graph_data[graph.attr('data-graph-content')][1] },
				{ label: "Failed",  data: graph_data[graph.attr('data-graph-content')][0] }
			], {
			series: {
				bars: { show: true },
				points: { show: true }
			},
			xaxis: {
				mode: "time",
			},
			yaxis: {
				ticks: 10,
				min: 0,
			},
			zoom: {
				interactive: true
			},
			pan: {
				interactive: true
			},
			grid: {
				backgroundColor: { colors: ["#fff", "#eee"] },
				hoverable: true,
				clickable: true,
				markings: [{ color: '#ccc', yaxis: { from: 0, to: -100000 } }]
			}
		});
	});

	//generate pie charts
	jQuery('.meow-pie').each(function(k,v){
		var pie = jQuery(this);
		jQuery.plot(pie, pie_data[pie.attr('data-pie-content')], {
			series: {
				pie: {
					show: true,
					radius: 1,
				},
				legend: {
					show: false
				}
			}
		});
	});

	//generate a tooltip popup for plot points
	function showTooltip(x, y, contents) {
		jQuery('<div id="tooltip">' + contents + '</div>').css( {
			position: 'absolute',
			display: 'none',
			top: y + 5,
			left: x + 5,
			border: '1px solid #fdd',
			padding: '2px',
			'background-color': '#fee',
			opacity: 0.80
		}).appendTo("body").fadeIn(200);
	}

	//figure out whether a tooltip should be shown and what it should say
	var previousPoint = null;
	jQuery(".meow-graph").bind("plothover", function (event, pos, item) {
		jQuery("#x").text(pos.x);
		jQuery("#y").text(pos.y);

		if (item) {
			if (previousPoint != item.dataIndex) {
				previousPoint = item.dataIndex;

				jQuery("#tooltip").remove();
				var date = new Date(item.datapoint[0]),
					x = months[date.getMonth()] + ' ' + date.getUTCDate(),
					y = item.datapoint[1];

				showTooltip(item.pageX, item.pageY,
					x + ': ' + y + ' ' + item.series.label);
			}
		}
		else {
			jQuery("#tooltip").remove();
			previousPoint = null;
		}

	});

});

</script>