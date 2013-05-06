<?php

function file_get_contents_curl($url) 
{
  $ch = curl_init();
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $url);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}

function getElapsedTime($eventTime)
{
    $totaldelay = time() - $eventTime;
    if($totaldelay <= 0)
    {
        return '';
    }
    else
    {
        if($days=floor($totaldelay/86400))
        {
            $totaldelay = $totaldelay % 86400;
            return $days.' days ago';
        }
        if($hours=floor($totaldelay/3600))
        {
            $totaldelay = $totaldelay % 3600;
            return $hours.' hours ago';
        }
        if($minutes=floor($totaldelay/60))
        {
            $totaldelay = $totaldelay % 60;
            return $minutes.' minutes ago';
        }
        if($seconds=floor($totaldelay/1))
        {
            $totaldelay = $totaldelay % 1;
            return $seconds.' seconds ago';
        }
    }
}
$api_key = "your_api_key";
$json = file_get_contents_curl('https://mining.bitcoin.cz/accounts/profile/json/'.$api_key );
$data = json_decode($json);

echo "<b>Username: </b>".$data->username . "<br>";
echo "<b>Confirmed BTC</b>: ". $data->confirmed_reward . " BTC<br>";
echo '<table border="1"><tr><td>Worker</td><td>Last share</td><td>Score</td><td>Hashrate</td><td>Shares</td><td>Alive</td>';

foreach ( $data->workers as $index => $worker )
{
	echo '
		<tr>
			<td>'.$index.'</td>
			<td>'.getElapsedTime($worker->last_share).'</td>
			<td>'.$worker->score.'</td>
			<td>'.$worker->hashrate.' Mhs/s</td>
			<td>'.$worker->shares.'</td>';
	if($worker->alive){
		echo '<td>Yes</td>';
	}
	else{
		echo '<td>No</td>';
	}
	
	echo '</tr>';
}
echo '</table>';
?>


