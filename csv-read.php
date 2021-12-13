<?php

	// function to take numerical input between low and high from user
	function number_picker($low, $high)
	{
		$ind = fgets(STDIN);
		while ((!is_numeric($ind)) || $ind < $low || $ind > $high) {
			echo "\nUnfortunately your input has not been recognised as valid\nCould you please input the number again (number between ".$low." and ".$high."): ";
			$ind = fgets(STDIN);
			if ($ind == 'exit') {exit;}
		}
		return $ind;
	}

	// create translations for code
	$country_codes = ['FR','DE','IT','PT','CZ','GB'];
	$country_trans = ['FR'=>'France','DE'=>'Germany','IT'=>'Italy','PT'=>'Portugal','CZ'=>'Czech Republic','GB'=>'Great Britain'];

	// to come back to if user wants to restart process
	start:

	echo "\nWhich country would you like to gather information for?";
	for ($i=0;$i<count($country_codes);$i++)
	{
		echo "\n".$i.') '.$country_trans[$country_codes[$i]];
	}

	echo "\nPlease input country index: ";
	$ind = number_picker(0,count($country_codes)-1);

	echo "\nThank you, you have selected: ".$country_trans[$country_codes[0+$ind]];

	$country_services = [];
	$ds = [];
	$country_counts = [];
	$row = 0;

	// open csv and read every line, if country code == chosen code, add service and center to array

  if (($handle = fopen("services.csv", "r")) !== FALSE) {
		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

			// 0th row is the column headers so can ignore
			if ($row)
			{
				// get all data from csv and seperate into different lists
				// capitalise country code so they are all the same
				$data[3] = strtoupper($data[3]);
				// 1) gather all data in dataset if needed for further options
				$ds[] = $data;
				// 2) 3rd array for the number of services per country
				$country_counts[] = $data[3];
				if ($data[3] == $country_codes[0+$ind])
				{
					// 3) the first to answer the initial question of what services under a chosen country
					$country_services[] = $data;
				}
			}

			$row++;
		}
		fclose($handle);
  }

	echo "\n\nWhich has the following services: ";

	// show serviced offered by that specific country
	$i = 0;
	foreach ($country_services as $service) {
		$i++;
		echo "\n".$i.") ".$service[2].", from the: ".$service[1]." center";
	}

	echo "\n\nWould you like to 1) start again, 2) look at a summary of the database or 3) exit: ";
	$continue = number_picker(1,4);

	if (0+$continue == 3) {exit;}
	else if (0+$continue == 1) {goto start;}
	else
	{
		// show summary of countries
		echo "\nHere is a summary of available services in other countries:";
		foreach (array_count_values($country_counts) as $key => $val)
		{
			$a = 'service';
			if ($val > 1) {$a = $a.'s';}
			echo "\n".$country_trans[$key]." has ".$val." ".$a;
		}
	}

?>