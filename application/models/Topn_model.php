<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Topn_model extends CI_Model  {
	private $premiumArr = array('status' => 'Fail', 'message' => '');
	
	
	private $single_medical_0_29 = array(
    21.84,
    21.84,
    21.84,
    21.84,
    21.84,
    21.84,
    21.84,
    22.68,
    25.20,
    28.56,
    30.24,
    33.60,
    36.96,
    39.48,
    42.84,
    44.52,
    47.88,
    51.24,
    53.76,
    57.12,
    58.80,
    62.16,
    64.68,
    68.04,
    71.40,
    73.08,
    76.44,
    78.96,
    82.32,
    84.84,
    87.36,
    91.56,
    94.92,
    97.44,
    100.80,
    104.16,
    105.84,
    109.20,
    111.72,
    115.08,
    117.60,
    120.12,
    123.48,
    126.84,
    128.52,
    131.88,
    134.40,
    137.76,
    141.12,
    142.80,
    146.16,
    149.52,
    152.04,
    155.40,
    157.08,
    160.44,
    163.80,
    166.32,
    169.68,
    172.20,
    182.28,
    185.64,
    189.00,
    191.52,
    194.04,
    197.40,
    200.76,
    203.28,
    206.64,
    210.00,
    213.36,
    215.04,
    218.40,
    221.76,
    225.12,
    226.80,
    230.16,
    233.52,
    236.88,
    239.40,
    242.76,
    246.12,
    248.64,
    251.16,
    254.52,
    257.88,
    261.24,
    262.92,
    266.28,
    269.64,
    272.16,
    275.52,
    278.88,
    281.40,
    283.92,
    287.28,
    290.64,
    294.00,
    295.68,
    299.04,
    302.40,
    305.76,
    308.28,
    311.64,
    314.16,
    317.52,
    320.04,
    323.40,
    326.76,
    330.12,
    331.80,
    335.16,
    338.52,
    341.88,
    344.40,
    346.92,
    350.28,
    353.64,
    356.16,
    359.52,
  );

	private $single_medical_30_54 = array(
    18.48,
    18.48,
    18.48,
    18.48,
    18.48,
    18.48,
    18.48,
    21.84,
    24.36,
    26.88,
    30.24,
    32.76,
    35.28,
    37.80,
    40.32,
    43.68,
    45.36,
    48.72,
    51.24,
    53.76,
    56.28,
    59.64,
    62.16,
    64.68,
    68.04,
    70.56,
    73.92,
    76.44,
    78.12,
    80.64,
    83.16,
    87.36,
    89.88,
    93.24,
    95.76,
    98.28,
    101.64,
    104.16,
    107.52,
    109.20,
    111.72,
    115.08,
    117.60,
    120.96,
    123.48,
    126.00,
    129.36,
    131.88,
    135.24,
    136.92,
    140.28,
    142.80,
    145.32,
    147.84,
    150.36,
    153.72,
    155.40,
    158.76,
    161.28,
    164.64,
    173.88,
    176.40,
    178.92,
    182.28,
    184.80,
    188.16,
    191.52,
    194.04,
    197.40,
    199.92,
    202.44,
    204.96,
    207.48,
    210.84,
    213.36,
    216.72,
    219.24,
    221.76,
    225.12,
    227.64,
    231.00,
    234.36,
    236.88,
    239.40,
    241.92,
    245.28,
    247.80,
    250.32,
    253.68,
    256.20,
    260.40,
    262.92,
    265.44,
    268.80,
    271.32,
    274.68,
    278.04,
    280.56,
    283.08,
    286.44,
    288.96,
    291.48,
    294.84,
    297.36,
    299.88,
    303.24,
    305.76,
    308.28,
    311.64,
    314.16,
    318.36,
    320.88,
    323.40,
    326.76,
    329.28,
    331.80,
    334.32,
    337.68,
    340.20,
    342.72,
  );

	private $single_medical_55_59 = array(
    19.32,
    19.32,
    19.32,
    19.32,
    21.84,
    26.88,
    30.24,
    35.28,
    39.48,
    44.52,
    48.72,
    52.92,
    57.12,
    62.16,
    65.52,
    71.40,
    74.76,
    79.80,
    84.00,
    88.20,
    92.40,
    97.44,
    101.64,
    105.84,
    110.04,
    115.08,
    118.44,
    124.32,
    127.68,
    131.88,
    136.92,
    141.12,
    147.00,
    150.36,
    155.40,
    159.60,
    163.80,
    168.00,
    173.04,
    177.24,
    182.28,
    185.64,
    190.68,
    194.04,
    199.92,
    203.28,
    208.32,
    212.52,
    216.72,
    222.60,
    225.96,
    231.00,
    235.20,
    239.40,
    243.60,
    248.64,
    252.84,
    257.88,
    261.24,
    266.28,
    320.88,
    325.08,
    330.12,
    336.84,
    341.04,
    346.08,
    351.96,
    357.00,
    361.20,
    367.08,
    372.96,
    378.84,
    383.04,
    388.92,
    393.96,
    399.00,
    404.04,
    409.92,
    414.96,
    420.00,
    425.88,
    430.08,
    435.12,
    441.84,
    446.04,
    451.08,
    457.80,
    462.00,
    467.04,
    472.92,
    477.96,
    482.16,
    488.04,
    493.08,
    498.12,
    504.00,
    509.04,
    514.08,
    519.96,
    525.00,
    530.04,
    535.92,
    540.96,
    545.16,
    551.04,
    556.08,
    561.12,
    567.00,
    572.04,
    577.08,
    582.96,
    588.00,
    593.04,
    598.92,
    603.12,
    608.16,
    614.04,
    619.08,
    624.12,
    630.00,
  );

	private $single_medical_60_64 = array(
    30.24,
    30.24,
    30.24,
    30.24,
    34.44,
    39.48,
    42.00,
    45.36,
    47.04,
    51.24,
    54.60,
    57.12,
    59.64,
    65.52,
    70.56,
    74.76,
    79.80,
    84.00,
    88.20,
    93.24,
    97.44,
    102.48,
    106.68,
    110.88,
    115.92,
    120.12,
    125.16,
    129.36,
    133.56,
    138.60,
    142.80,
    147.84,
    152.04,
    156.24,
    161.28,
    165.48,
    170.52,
    174.72,
    178.92,
    183.96,
    188.16,
    193.20,
    197.40,
    201.60,
    206.64,
    210.84,
    215.88,
    220.08,
    224.28,
    229.32,
    233.52,
    239.40,
    248.64,
    257.88,
    262.92,
    270.48,
    277.20,
    283.92,
    292.32,
    300.72,
    342.82,
    347.31,
    352.69,
    359.87,
    364.36,
    369.74,
    376.03,
    381.41,
    385.90,
    392.18,
    398.46,
    404.74,
    409.23,
    415.51,
    420.90,
    426.28,
    431.67,
    437.95,
    443.33,
    448.72,
    455.00,
    459.49,
    464.87,
    472.05,
    476.54,
    481.92,
    489.10,
    493.59,
    498.97,
    505.26,
  );

	private $single_medical_65_69 = array(
    33.60,
    33.60,
    34.44,
    34.44,
    41.16,
    47.04,
    49.56,
    54.60,
    57.96,
    63.00,
    69.72,
    72.24,
    78.12,
    80.64,
    84.00,
    89.88,
    99.12,
    104.16,
    110.04,
    120.12,
    125.16,
    131.04,
    136.08,
    142.80,
    149.52,
    155.40,
    162.12,
    168.00,
    175.56,
    183.12,
    189.84,
    194.04,
    199.92,
    207.48,
    213.36,
    217.56,
    225.96,
    231.84,
    236.04,
    244.44,
    247.80,
    256.20,
    262.08,
    269.64,
    275.52,
    283.92,
    290.64,
    299.88,
    308.28,
    316.68,
    325.08,
    330.96,
    339.36,
    349.44,
    357.00,
    366.24,
    374.64,
    380.52,
    388.08,
    399.84,
    455.82,
    460.38,
    469.58,
    478.97,
    483.76,
    488.60,
    498.37,
    503.36,
    508.39,
    518.56,
    523.75,
    534.22,
    539.56,
    550.35,
    555.86,
    561.42,
    567.03,
    572.70,
    578.43,
    584.21,
    590.05,
    595.95,
    601.91,
    613.95,
    620.09,
    626.29,
    632.56,
    638.88,
    645.27,
    651.72,
  );

	private $single_medical_70_74 = array(
    49.56,
    52.92,
    52.92,
    58.80,
    65.52,
    72.24,
    78.12,
    83.16,
    90.72,
    96.60,
    104.16,
    110.04,
    120.12,
    125.16,
    131.88,
    141.12,
    147.84,
    155.40,
    163.80,
    172.20,
    177.24,
    185.64,
    194.88,
    204.12,
    210.84,
    216.72,
    227.64,
    234.36,
    241.92,
    252.00,
    259.56,
    269.64,
    279.72,
    287.28,
    296.52,
    307.44,
    316.68,
    325.92,
    335.16,
    346.08,
    356.16,
    363.72,
    374.64,
    386.40,
    400.68,
    415.80,
    431.76,
    449.40,
    466.20,
    484.68,
    498.12,
    511.56,
    535.08,
    548.52,
    561.96,
    575.40,
    588.00,
    601.44,
    615.72,
    615.72,
    630.90,
    637.21,
    649.95,
    662.95,
    669.58,
    676.28,
    689.80,
    696.70,
    703.67,
    717.74,
    724.92,
    739.42,
    746.81,
    761.75,
    769.37,
    777.06,
    784.83,
    792.68,
    800.60,
    808.61,
    816.70,
    824.86,
    833.11,
    849.77,
    858.27,
    866.85,
    875.52,
    884.28,
    893.12,
    902.05,
  );
	
	private $single_medical_75_79 = array(
    68.04,
    68.04,
    72.24,
    72.24,
    80.64,
    88.20,
    98.28,
    105.84,
    119.28,
    131.88,
    145.32,
    158.76,
    172.20,
    184.80,
    197.40,
    210.84,
    225.12,
    237.72,
    251.16,
    264.60,
    277.20,
    290.64,
    303.24,
    316.68,
    330.12,
    342.72,
    356.16,
    370.44,
    383.04,
    396.48,
    409.92,
    422.52,
    435.96,
    448.56,
    462.00,
    475.44,
    488.04,
    501.48,
    515.76,
    528.36,
    541.80,
    554.40,
    567.84,
    581.28,
    593.88,
    607.32,
    619.92,
    633.36,
    646.80,
    661.08,
    673.68,
    687.12,
    699.72,
    713.16,
    726.60,
    739.20,
    752.64,
    765.24,
    778.68,
    792.12,
    901.32,
    910.33,
    928.54,
    947.11,
    956.58,
    966.15,
    985.47,
    995.33,
    1005.28,
    1025.38,
    1035.64,
    1056.35,
    1066.91,
    1088.25,
    1099.13,
    1110.13,
    1121.23,
    1132.44,
    1143.76,
    1155.20,
    1166.75,
    1178.42,
    1190.21,
    1214.01,
    1226.15,
    1238.41,
    1250.80,
    1263.30,
    1275.94,
    1288.70,
  );

	private $single_medical_80_84 = array(
    120.12,
    120.12,
    126.84,
    126.84,
    141.96,
    157.08,
    174.72,
    188.16,
    210.84,
    233.52,
    257.04,
    280.56,
    304.92,
    327.60,
    350.28,
    373.80,
    397.32,
    421.68,
    444.36,
    467.04,
    490.56,
    514.08,
    538.44,
    561.12,
    585.48,
    608.16,
    630.84,
    655.20,
    677.88,
    702.24,
    724.92,
    748.44,
    771.12,
    794.64,
    819.00,
    841.68,
    865.20,
    887.88,
    912.24,
    935.76,
    958.44,
    982.80,
    1005.48,
    1029.00,
    1052.52,
    1076.04,
    1099.56,
    1122.24,
    1145.76,
    1169.28,
    1192.80,
    1216.32,
    1239.84,
    1262.52,
    1285.20,
    1309.56,
    1333.08,
    1356.60,
    1380.12,
    1403.64,
    1600.15,
    1616.15,
    1643.69,
    1671.71,
    1688.42,
    1705.30,
    1734.37,
    1751.71,
    1769.23,
    1799.38,
    1817.37,
    1848.35,
    1866.83,
    1898.64,
    1917.63,
    1936.81,
    1956.17,
    1975.74,
    1995.49,
    2015.45,
    2035.60,
    2055.96,
    2076.52,
    2111.91,
    2133.03,
    2154.36,
    2175.90,
    2197.66,
    2219.64,
    2241.83,
  );
	
	private $single_medical_85_89 = array(
    174.17,
    174.17,
    183.92,
    183.92,
    205.84,
    227.77,
    253.34,
    272.83,
    305.72,
    338.60,
    372.71,
    406.81,
    442.13,
    475.02,
    507.91,
    542.01,
    576.11,
    611.44,
    644.32,
    677.21,
    711.31,
    745.42,
    780.74,
    813.62,
    848.95,
    881.83,
    914.72,
    950.04,
    982.93,
    1018.25,
    1051.13,
    1085.24,
    1118.12,
    1152.23,
    1187.55,
    1220.44,
    1254.54,
    1287.43,
    1322.75,
    1356.85,
    1389.74,
    1425.06,
    1457.95,
    1492.05,
    1526.15,
    1560.26,
    1594.36,
    1627.25,
    1661.35,
    1695.46,
    1729.56,
    1763.66,
    1797.77,
    1830.65,
    1863.54,
    1898.86,
    1932.97,
    1967.07,
    2001.17,
    2035.28,
    2320.22,
    2343.42,
    2383.35,
    2423.98,
    2448.21,
    2472.69,
    2514.84,
    2539.98,
    2565.38,
    2609.10,
    2635.19,
    2680.11,
    2706.91,
    2753.03,
    2780.56,
    2808.37,
    2836.45,
    2864.82,
    2893.46,
    2922.40,
    2951.63,
    2981.14,
    3010.95,
    3062.27,
    3092.89,
    3123.82,
    3155.06,
    3186.61,
    3218.48,
    3250.65,
  );
	
	private $ad_and_d = array(
			'25000' => 0.12,
			'50000' => 0.24,
			'75000' => 0.36,
			'100000' => 0.48,
	);

	private $flight_accident = array(
			'100000' => 0.9,
			'200000' => 1.8,
			'300000' => 2.7,
	);
	
	private function optional_plan($data, $ischeck) {
		if (!empty($data['ad_and_d_ck'])) {
			$rate = isset($this->ad_and_d[$data['ad_and_d_insured']]) ? $this->ad_and_d[$data['ad_and_d_insured']] : 0;
			if (empty($rate)) {
				$this->premiumArr['message'] = "Unknown AD & D insured amount. Please select insured amount";
				$this->premiumArr['active_tab'] = 'packages_tab';
				$this->premiumArr['premium'] = 0;
				return 0;
			}
			if ($data['age'] >= 90) {
				$this->premiumArr['message'] = 'Age must be equal or less than 90 for AD & D insured plan.';
				$this->premiumArr['active_tab'] = 'packages_tab';
				$this->premiumArr['premium'] = 0;
				return 0;
			}
			
			if ($data['isfamilyplan'] == 1) {
				$rate *= 2.25;
			} else if ($data['isfamilyplan'] == 2) {
				$rate *= $data['people_number'];
			}
			
			$this->premiumArr['premium'] += $rate * $data['totaldays'];
		}
			
		if (!empty($data['flight_accident_ck'])) {
			$rate = isset($this->flight_accident[$data['flight_accident_insured']]) ? $this->flight_accident[$data['flight_accident_insured']] : 0;
			if (empty($rate)) {
				$this->premiumArr['message'] = "Unknown flight accident insured amount. Please select insured amount";
				$this->premiumArr['active_tab'] = 'packages_tab';
				$this->premiumArr['premium'] = 0;
				return 0;
			}
			if ($data['age'] >= 90) {
				$this->premiumArr['message'] = 'Age must be equal or less than 90 for Flight Accident insured plan.';
				$this->premiumArr['active_tab'] = 'packages_tab';
				$this->premiumArr['premium'] = 0;
				return 0;
			}

			if ($data['isfamilyplan'] == 1) {
				$rate *= 2.25;
			} else if ($data['isfamilyplan'] == 2) {
				$rate *= $data['people_number'];
			}
			
			$this->premiumArr['premium'] += $rate;
		}
		
		if ($this->premiumArr['premium'] > 0) {
			$this->premiumArr['status'] = 'OK';
		}

		return 1;
	}
	
	private function single_medical_plan($data, $ischeck) {
		$arr = 'single_medical';
		$mindays = 1;
		$maxdays = 90;
		if ($data['age'] < 30) {
			$arr = 'single_medical_0_29';
      $maxdays = 120;
		} else if ($data['age'] < 55) {
			$arr = 'single_medical_30_54';
      $maxdays = 120;
		} else if ($data['age'] < 60) {
			$arr = 'single_medical_55_59';
      $maxdays = 120;
		} else if ($data['age'] < 65) {
			$arr = 'single_medical_60_64';
		} else if ($data['age'] < 70) {
			$arr = 'single_medical_65_69';
		} else if ($data['age'] < 75) {
			$arr = 'single_medical_70_74';
		} else if ($data['age'] < 80) {
			$arr = 'single_medical_75_79';
		} else if ($data['age'] < 85) {
			$arr = 'single_medical_80_84';
		} else if ($data['age'] < 90) {
			$arr = 'single_medical_85_89';
		} else {
			$this->premiumArr['message'] = 'Age must be equal or less than 84.';
			$this->premiumArr['active_tab'] = 'date_members_tab';
			return 0;
		}
		
		if (($data['totaldays'] < $mindays) || ($data['totaldays'] > $maxdays)) {
			$this->premiumArr['message'] = 'Please select days less than 90.';
			$this->premiumArr['active_tab'] = 'date_members_tab';
			return 1;
		}
		
		$dayidx = $data['totaldays'] - $mindays;	// change to index
		
		if (!isset($this->{$arr}) || !is_array($this->{$arr}) || !isset($this->{$arr}[$dayidx])) {
			$this->premiumArr['message'] = 'Out of condition range. Please change days or other conditions';
			return 0;
		}
		
		if ($ischeck) {
			return 1;
		}
		
		$this->premiumArr['premium'] = $this->{$arr}[$dayidx];
		
		if ($data['isfamilyplan'] == 1) {
			$this->premiumArr['premium'] *= 2.25;		// single premium times 2.25
		} else if ($data['isfamilyplan'] == 2) {
			$this->premiumArr['premium'] *= $data['people_number'];
			if ($data['people_number'] > 9) {
				$this->premiumArr['premium'] *= 0.9;	// 10% discount apply if number of members is 10 or more
			}
		}
		
		if ($this->premiumArr['premium'] > 0) {
			return $this->optional_plan($data, 0);
		} else {
			$this->premiumArr['message'] = "This option is not available, the policy will cover pre-existing condition automatically.  Please change coverage to 'with stable pre-existing medical condition'.";
		}

		return 1;
	}
	
	public function get_premium($passdata) {
		$this->premiumArr['premium'] = 0;
		$this->premiumArr['single_medical_plan'] = $this->single_medical_plan($passdata, 1);
		$this->premiumArr['optional_plan'] = $this->optional_plan($passdata, 1);
		$this->premiumArr['questionnaire'] = 0;
		$this->premiumArr['stable_condition'] = 0;
		$this->premiumArr['tax'] = 0;
		$this->premiumArr['totalyears'] = $passdata['age'];
		$this->premiumArr['totaldays'] = $passdata['totaldays'];
		$this->premiumArr['sum_insured'] = $passdata['sum_insured'];
		$this->premiumArr['deductible_amount'] =  0;
		$this->premiumArr['from_top'] =  1;
		$this->premiumArr['status'] = 'Fail';
		$this->premiumArr['message'] = '';
		$this->premiumArr['active_tab'] = '';
		
		if (($passdata['people_number'] > 1) && empty($passdata['isfamilyplan'])) {
			$this->premiumArr['message'] = 'Please select family or group for more than one member';
			$this->premiumArr['active_tab'] = 'date_members_tab';
		} else {
			$package = $passdata['package'];
			if (method_exists($this, $package)) {
				$this->$package($passdata, 0);
			}
			if (($passdata['isfamilyplan'] == 2) && $passdata['questionnaire']) {
				$this->premiumArr['questionnaire'] = 0;
				$this->premiumArr['premium'] = 0;
				$this->premiumArr['status'] = 'Fail';
				$this->premiumArr['message'] = "The oldest age people is over group member conditions";
				$this->premiumArr['active_tab'] = 'date_members_tab';
			}

			if (($this->premiumArr['status'] == 'OK') && ($passdata['isfamilyplan'] == 2)) {
				// Passed check, re-calculate premium
				$premium = 0;
				$tax = 0;
				$people_number = $passdata['people_number'];
				$passdata['people_number'] = 1;
				$passdata['isfamilyplan'] = 0;
				foreach ($passdata['agearr'] as $age) {
					$passdata['age'] = $age;
					$this->premiumArr['premium'] = 0;
					$this->premiumArr['tax'] = 0;
					$this->$package($passdata, 0);
					$premium += $this->premiumArr['premium'];
					$tax += $this->premiumArr['tax'];
				}
				if ($people_number > 9) {
					$premium *= 0.9;	// 10% discount apply if number of members is 10 or more
					$tax *= 0.9;	// 10% discount apply if number of members is 10 or more
				}
				$this->premiumArr['premium'] = $premium; 
				$this->premiumArr['tax'] = $tax; 
			}

			if (($this->premiumArr['premium'] > 0) && ($this->premiumArr['premium'] < 25)) {
				$this->premiumArr['premium'] = 25;
			}
			if ($this->premiumArr['premium'] > 0) {
        $this->premiumArr['premium'] = floatval(number_format($this->premiumArr['premium'], 2, '.', ''));
      }
		}
		return $this->premiumArr;
	}
	
	public function refund_amount($plan, $days) {
		if (empty($plan) || ($plan['totaldays'] <= $days)) {
			return 0;
		}
		if ($plan['package'] == 'single_medical_plan') {
			$para = array();
			$para['age'] = $plan['totalyears'];
			$para['totaldays'] = $plan['totaldays'];
			$para['questionnaire'] = $plan['questionnaire'];
			$para['stable_condition'] = $plan['stable_condition'];
			$para['isfamilyplan'] = $plan['isfamilyplan'];
			$para['province2'] = $plan['province2'];
			$para['people_number'] = 1;
			if ($para['isfamilyplan'] == 2) {
				// Group plan
				$this->load->model('customer_model');
				$this->load->model('product_model');
				
				$para['isfamilyplan'] = 0;
				
				$customer = $this->customer_model->get_customer_by_id($plan['customer_id']);
				$para['age'] = $this->product_model->getYears($plan['effective_date'], $customer['birthday']);
				$this->single_medical_plan($para, 0);
				$premium = $this->premiumArr['premium'];
				$tax = $this->premiumArr['tax'];
				
				$customers = $this->customer_model->get_customer_by_parent_id($plan['customer_id']);
				foreach ($customers as $customer) {
					$para['age'] = $this->product_model->getYears($plan['effective_date'], $customer['birthday']);
					$this->single_medical_plan($para, 0);
					$premium += $this->premiumArr['premium'];
					$tax += $this->premiumArr['tax'];
				}
				$this->premiumArr['premium'] = $premium;
				$this->premiumArr['tax'] = $tax;
			} else {
				$this->single_medical_plan($para, 0);
			}
			if ($this->premiumArr['status'] == 'OK') {
        $usedamount = $this->premiumArr['premium'] * $days / $plan['totaldays'];
        if ($usedamount < 25) {
          $usedamount = 25;
        }

				$refund = $this->premiumArr['premium'] - $usedamount;
 				if ($refund > 0) {
					return round($refund, 2);
				}
			}
		}
		return 0;
	}
}
