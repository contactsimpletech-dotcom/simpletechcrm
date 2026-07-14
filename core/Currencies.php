<?php
/**
 * Single source of truth for all currency choices in the platform.
 *
 * To add or remove a currency, edit ONE place: $list below.
 * All views/modules call Currencies::renderOptions() or Currencies::list().
 *
 * v2: extended from 19 to 156 currencies (worldwide coverage). The
 * original 19 remain first in their original order with their original
 * symbols so existing UIs render identically at the top of selects.
 */
class Currencies {
    /**
     * code => [name, symbol]
     * Add/remove here only. Order = display order.
     */
    private static array $list = [
        'USD'   => ['US Dollar',                     '$'],
        'EUR'   => ['Euro',                          '€'],
        'GBP'   => ['British Pound',                 '£'],
        'CAD'   => ['Canadian Dollar',               'C$'],
        'AUD'   => ['Australian Dollar',             'A$'],
        'NZD'   => ['New Zealand Dollar',            'NZ$'],
        'ZAR'   => ['South African Rand',            'R'],
        'JPY'   => ['Japanese Yen',                  '¥'],
        'INR'   => ['Indian Rupee',                  '₹'],
        'BRL'   => ['Brazilian Real',                'R$'],
        'MXN'   => ['Mexican Peso',                  'Mex$'],
        'CHF'   => ['Swiss Franc',                   'CHF'],
        'SEK'   => ['Swedish Krona',                 'kr'],
        'NOK'   => ['Norwegian Krone',               'kr'],
        'DKK'   => ['Danish Krone',                  'kr'],
        'SGD'   => ['Singapore Dollar',              'S$'],
        'HKD'   => ['Hong Kong Dollar',              'HK$'],
        'AED'   => ['UAE Dirham',                    'د.إ'],
        'ILS'   => ['Israeli Shekel',                '₪'],
        // ---- extended list (v2) - alphabetical ----
        'AFN'   => ['Afghan Afghani',                '؋'],
        'ALL'   => ['Albanian Lek',                  'L'],
        'AMD'   => ['Armenian Dram',                 '֏'],
        'ANG'   => ['Netherlands Antillean Guilder', 'ƒ'],
        'AOA'   => ['Angolan Kwanza',                'Kz'],
        'ARS'   => ['Argentine Peso',                'AR$'],
        'AWG'   => ['Aruban Florin',                 'ƒ'],
        'AZN'   => ['Azerbaijani Manat',             '₼'],
        'BAM'   => ['Bosnian Convertible Mark',      'KM'],
        'BBD'   => ['Barbadian Dollar',              'Bds$'],
        'BDT'   => ['Bangladeshi Taka',              '৳'],
        'BGN'   => ['Bulgarian Lev',                 'лв'],
        'BHD'   => ['Bahraini Dinar',                'BHD'],
        'BIF'   => ['Burundian Franc',               'FBu'],
        'BMD'   => ['Bermudian Dollar',              'BD$'],
        'BND'   => ['Brunei Dollar',                 'B$'],
        'BOB'   => ['Bolivian Boliviano',            'Bs'],
        'BSD'   => ['Bahamian Dollar',               'B$'],
        'BTN'   => ['Bhutanese Ngultrum',            'Nu.'],
        'BWP'   => ['Botswana Pula',                 'P'],
        'BYN'   => ['Belarusian Ruble',              'Br'],
        'BZD'   => ['Belize Dollar',                 'BZ$'],
        'CDF'   => ['Congolese Franc',               'FC'],
        'CLP'   => ['Chilean Peso',                  'CL$'],
        'CNY'   => ['Chinese Yuan',                  '¥'],
        'COP'   => ['Colombian Peso',                'CO$'],
        'CRC'   => ['Costa Rican Colon',             '₡'],
        'CUP'   => ['Cuban Peso',                    '$MN'],
        'CVE'   => ['Cape Verdean Escudo',           'Esc'],
        'CZK'   => ['Czech Koruna',                  'Kč'],
        'DJF'   => ['Djiboutian Franc',              'Fdj'],
        'DOP'   => ['Dominican Peso',                'RD$'],
        'DZD'   => ['Algerian Dinar',                'DA'],
        'EGP'   => ['Egyptian Pound',                'E£'],
        'ERN'   => ['Eritrean Nakfa',                'Nfk'],
        'ETB'   => ['Ethiopian Birr',                'Br'],
        'FJD'   => ['Fijian Dollar',                 'FJ$'],
        'FKP'   => ['Falkland Islands Pound',        '£'],
        'GEL'   => ['Georgian Lari',                 '₾'],
        'GGP'   => ['Guernsey Pound',                '£'],
        'GHS'   => ['Ghanaian Cedi',                 'GH₵'],
        'GIP'   => ['Gibraltar Pound',               '£'],
        'GMD'   => ['Gambian Dalasi',                'D'],
        'GNF'   => ['Guinean Franc',                 'FG'],
        'GTQ'   => ['Guatemalan Quetzal',            'Q'],
        'GYD'   => ['Guyanese Dollar',               'G$'],
        'HNL'   => ['Honduran Lempira',              'L'],
        'HTG'   => ['Haitian Gourde',                'G'],
        'HUF'   => ['Hungarian Forint',              'Ft'],
        'IDR'   => ['Indonesian Rupiah',             'Rp'],
        'IMP'   => ['Isle of Man Pound',             '£'],
        'IQD'   => ['Iraqi Dinar',                   'IQD'],
        'IRR'   => ['Iranian Rial',                  '﷼'],
        'ISK'   => ['Icelandic Krona',               'kr'],
        'JEP'   => ['Jersey Pound',                  '£'],
        'JMD'   => ['Jamaican Dollar',               'J$'],
        'JOD'   => ['Jordanian Dinar',               'JOD'],
        'KES'   => ['Kenyan Shilling',               'KSh'],
        'KGS'   => ['Kyrgyzstani Som',               '⃀'],
        'KHR'   => ['Cambodian Riel',                '៛'],
        'KMF'   => ['Comorian Franc',                'CF'],
        'KPW'   => ['North Korean Won',              '₩'],
        'KRW'   => ['South Korean Won',              '₩'],
        'KWD'   => ['Kuwaiti Dinar',                 'KWD'],
        'KYD'   => ['Cayman Islands Dollar',         'CI$'],
        'KZT'   => ['Kazakhstani Tenge',             '₸'],
        'LAK'   => ['Lao Kip',                       '₭'],
        'LBP'   => ['Lebanese Pound',                'LBP'],
        'LKR'   => ['Sri Lankan Rupee',              'Rs'],
        'LRD'   => ['Liberian Dollar',               'L$'],
        'LSL'   => ['Lesotho Loti',                  'M'],
        'LYD'   => ['Libyan Dinar',                  'LD'],
        'MAD'   => ['Moroccan Dirham',               'MAD'],
        'MDL'   => ['Moldovan Leu',                  'L'],
        'MGA'   => ['Malagasy Ariary',               'Ar'],
        'MKD'   => ['Macedonian Denar',              'den'],
        'MMK'   => ['Myanmar Kyat',                  'K'],
        'MNT'   => ['Mongolian Tugrik',              '₮'],
        'MOP'   => ['Macanese Pataca',               'MOP$'],
        'MUR'   => ['Mauritian Rupee',               'Rs'],
        'MVR'   => ['Maldivian Rufiyaa',             'Rf'],
        'MWK'   => ['Malawian Kwacha',               'MK'],
        'MYR'   => ['Malaysian Ringgit',             'RM'],
        'MZN'   => ['Mozambican Metical',            'MT'],
        'NAD'   => ['Namibian Dollar',               'N$'],
        'NGN'   => ['Nigerian Naira',                '₦'],
        'NIO'   => ['Nicaraguan Cordoba',            'C$'],
        'NPR'   => ['Nepalese Rupee',                'Rs'],
        'OMR'   => ['Omani Rial',                    'OMR'],
        'PAB'   => ['Panamanian Balboa',             'B/.'],
        'PEN'   => ['Peruvian Sol',                  'S/'],
        'PGK'   => ['Papua New Guinean Kina',        'K'],
        'PHP'   => ['Philippine Peso',               '₱'],
        'PKR'   => ['Pakistani Rupee',               '₨'],
        'PLN'   => ['Polish Zloty',                  'zł'],
        'PYG'   => ['Paraguayan Guarani',            '₲'],
        'QAR'   => ['Qatari Riyal',                  'QAR'],
        'RON'   => ['Romanian Leu',                  'lei'],
        'RSD'   => ['Serbian Dinar',                 'din'],
        'RUB'   => ['Russian Ruble',                 '₽'],
        'RWF'   => ['Rwandan Franc',                 'RF'],
        'SAR'   => ['Saudi Riyal',                   'SAR'],
        'SBD'   => ['Solomon Islands Dollar',        'SI$'],
        'SCR'   => ['Seychellois Rupee',             'Rs'],
        'SDG'   => ['Sudanese Pound',                'SDG'],
        'SHP'   => ['Saint Helena Pound',            '£'],
        'SLE'   => ['Sierra Leonean Leone',          'Le'],
        'SOS'   => ['Somali Shilling',               'Sh'],
        'SRD'   => ['Surinamese Dollar',             'Sr$'],
        'SSP'   => ['South Sudanese Pound',          'SSP'],
        'STN'   => ['Sao Tome Dobra',                'Db'],
        'SYP'   => ['Syrian Pound',                  'SYP'],
        'SZL'   => ['Swazi Lilangeni',               'E'],
        'THB'   => ['Thai Baht',                     '฿'],
        'TJS'   => ['Tajikistani Somoni',            'SM'],
        'TMT'   => ['Turkmenistani Manat',           'm'],
        'TND'   => ['Tunisian Dinar',                'TND'],
        'TOP'   => ['Tongan Paanga',                 'T$'],
        'TRY'   => ['Turkish Lira',                  '₺'],
        'TTD'   => ['Trinidad and Tobago Dollar',    'TT$'],
        'TWD'   => ['New Taiwan Dollar',             'NT$'],
        'TZS'   => ['Tanzanian Shilling',            'TSh'],
        'UAH'   => ['Ukrainian Hryvnia',             '₴'],
        'UGX'   => ['Ugandan Shilling',              'USh'],
        'UYU'   => ['Uruguayan Peso',                '$U'],
        'UZS'   => ['Uzbekistani Som',               'so\'m'],
        'VES'   => ['Venezuelan Bolivar',            'Bs'],
        'VND'   => ['Vietnamese Dong',               '₫'],
        'VUV'   => ['Vanuatu Vatu',                  'VT'],
        'WST'   => ['Samoan Tala',                   'WS$'],
        'XAF'   => ['Central African CFA Franc',     'FCFA'],
        'XCD'   => ['East Caribbean Dollar',         'EC$'],
        'XOF'   => ['West African CFA Franc',        'CFA'],
        'XPF'   => ['CFP Franc',                     'CFP'],
        'YER'   => ['Yemeni Rial',                   'YER'],
        'ZMW'   => ['Zambian Kwacha',                'ZK'],
        'ZWL'   => ['Zimbabwean Dollar',             'Z$'],
    ];
    /** ['USD' => 'US Dollar', 'EUR' => 'Euro', ...] */
    public static function list(): array {
        $out = [];
        foreach (self::$list as $code => $info) $out[$code] = $info[0];
        return $out;
    }
    /** ['USD','EUR','GBP', ...] */
    public static function codes(): array {
        return array_keys(self::$list);
    }
    /** Long label like "USD - US Dollar" */
    public static function label(?string $code): string {
        $code = strtoupper((string)$code);
        return isset(self::$list[$code]) ? "{$code} - " . self::$list[$code][0] : $code;
    }
    public static function nameOf(?string $code): string {
        $code = strtoupper((string)$code);
        return self::$list[$code][0] ?? $code;
    }
    public static function symbolOf(?string $code): string {
        $code = strtoupper((string)$code);
        return self::$list[$code][1] ?? $code;
    }
    /**
     * Returns HTML <option> tags ready to drop inside <select>.
     * @param string $selected   Currency code currently selected
     * @param bool   $useLabel   If true: "USD - US Dollar". If false: "USD" only
     */
    public static function renderOptions(?string $selected = 'USD', bool $useLabel = false): string {
        $selected = strtoupper((string)$selected) ?: 'USD';
        $html = '';
        foreach (self::$list as $code => [$name, $symbol]) {
            $sel = $code === $selected ? ' selected' : '';
            $txt = $useLabel ? "{$code} - {$name}" : $code;
            $html .= "<option value=\"{$code}\"{$sel}>{$txt}</option>\n";
        }
        // keep a previously saved unknown value selectable
        if (!isset(self::$list[$selected])) {
            $html = "<option value=\"{$selected}\" selected>{$selected}</option>\n" . $html;
        }
        return $html;
    }
}
