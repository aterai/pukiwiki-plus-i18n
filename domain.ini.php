<?php

// $Id: domain.ini.php,v 1.2 2007/07/15 15:23:23 henoheno Exp $
// Domain related setting

// Domains who have 2nd and/or 3rd level domains
$domain = array();
$_pattern = array();

// ------------------------------
// ccTLD: Antigua and Barbuda
// NIC  : http://www.nic.ag/
// Whois: http://ns1.nic.ag/tools/whois.pl
$domain['ag'] = array(
    // AG Blocked or Reserved Domain Names Policy
    // http://www.nic.ag/reserved-names-policy.htm
    // "Available extensions are .AG, .COM.AG, .ORG.AG, .NET.AG, .CO.AG, and .NOM.AG."
    // http://www.nic.ag/
    'co' => true,
    'com' => true,
    'net' => true,
    'nom' => true,
    'org' => true,
);

// ------------------------------
// ccTLD: Australia
// http://www.auda.org.au/
// NIC  : http://www.aunic.net/
// Whois: http://www.ausregistry.com.au/
$_pattern['au']['geo'] = array(
    // Geographic
    'act' => true, // Australian Capital Territory
    'nt' => true, // Northern Territory
    'nsw' => true, // New South Wales
    'qld' => true, // Queensland
    'sa' => true, // South Australia
    'tas' => true, // Tasmania
    'vic' => true, // Victoria
    'wa' => true, // Western Australia
);
$domain['au'] = array(
    // .au Second Level Domains
    // http://www.auda.org.au/domains/
    'asn' => true,
    'com' => true,
    'conf' => true,
    'csiro' => true,
    'edu' => &$_pattern['au']['geo'],
    'gov' => &$_pattern['au']['geo'],
    'id' => true,
    'net' => true,
    'org' => true,
    'info' => true,
);

// ------------------------------
// ccTLD: Bahrain
// NIC  : http://www.inet.com.bh/ (.bh policies not found)
// Whois: (Not available) http://www.inet.com.bh/
$domain['bh'] = array(
    // Observed
    'com' => true,
    'edu' => true,
    'gov' => true,
    'org' => true,
);

// ------------------------------
// ccTLD: Brazil
// NIC  : http://registro.br/
// Whois:
$domain['br'] = array(
    // Info: Lista de categorias de dominios
    // http://registro.br/info/dpn.html

    // Categories for institutions
    'agr' => true, // Agricultural
    'am' => true, // Broadcasting
    'art' => true, // Art
    'com' => true,
    'coop' => true, // Cooperative
    'edu' => true,
    'esp' => true, // Sport
    'etc' => true, // Others
    'far' => true, // Pharmaceutical
    'fm' => true, // Broadcasting
    'g12' => true, // Educational
    'gov' => true,
    'imb' => true, // Real estate related
    'ind' => true, // Industrial
    'inf' => true, // Informational
    'mil' => true,
    'net' => true,
    'org' => true,
    'psi' => true, // Internet service providers
    'rec' => true, // Recreation, entertainment related
    'srv' => true, // Service-oriented
    'tmp' => true,
    'tur' => true, // Tour business
    'tv' => true,
    // Categories for professionals
    'adm' => true, // Administrators
    'adv' => true, // Advocates (Lawers)
    'arq' => true, // Architects
    'ato' => true, // Actors
    'bio' => true, // Biologists
    'bmd' => true, // Biomedics
    'cim' => true, // Correctors
    'cng' => true, // Scenographers
    'cnt' => true, // Counter (Accountants)
    'ecn' => true, // Economists
    'eng' => true, // Engineers
    'eti' => true, // IT specialists
    'fnd' => true, // 'Fonoaudiologos', Speech therapists?
    'fot' => true, // Photographers
    'fst' => true, // Physiotherapists
    'ggf' => true, // Geographers
    'jor' => true, // Journalists
    'lel' => true, // Auctioneers
    'mat' => true, // Mathematicians and Statisticians
    'med' => true, // Doctors
    'mus' => true, // Musicians
    'not' => true, // Notaries
    'ntr' => true, // Nutritionists
    'odo' => true, // Dentists
    'ppg' => true, // (Propaganda) Advertising executives and professionals
    'pro' => true, // Professors
    'psc' => true, // Psychologists
    'qsl' => true, // Amateur radio operators
    'slg' => true, // Sociologists
    'trd' => true, // Translators
    'vet' => true, // Veterinarians
    'zlg' => true, // Zoologists
    // Categories for people
    'blog' => true,
    'flog' => true,
    'nom' => true,
    'vlog' => true,
    'wiki' => true,
);

// ------------------------------
// ccTLD: China
// NIC  : http://www.cnnic.net.cn/en/index/
// Whois: http://ewhois.cnnic.cn/
$domain['cn'] = array(
    // Provisional Administrative Rules for Registration of Domain Names in China
    // http://www.cnnic.net.cn/html/Dir/2003/11/27/1520.htm

    // Organizational
    'ac' => true,
    'com' => true,
    'edu' => true,
    'gov' => true,
    'net' => true,
    'org' => true,
    // Geographic
    'ah' => true,
    'bj' => true,
    'cq' => true,
    'fj' => true,
    'gd' => true,
    'gs' => true,
    'gx' => true,
    'gz' => true,
    'ha' => true,
    'hb' => true,
    'he' => true,
    'hi' => true,
    'hk' => true,
    'hl' => true,
    'hn' => true,
    'jl' => true,
    'js' => true,
    'jx' => true,
    'ln' => true,
    'mo' => true,
    'nm' => true,
    'nx' => true,
    'qh' => true,
    'sc' => true,
    'sd' => true,
    'sh' => true,
    'sn' => true,
    'sx' => true,
    'tj' => true,
    'tw' => true,
    'xj' => true,
    'xz' => true,
    'yn' => true,
    'zj' => true,
);

// ------------------------------
// ccTLD: India
// NIC  : http://www.inregistry.in/
// Whois: http://www.inregistry.in/whois_search/
$domain['in'] = array(
    // Policies http://www.inregistry.in/policies/
    'ac' => true,
    'co' => true,
    'firm' => true,
    'gen' => true,
    'gov' => true,
    'ind' => true,
    'mil' => true,
    'net' => true,
    'org' => true,
    'res' => true,
    // Reserved Names by the government (for the 2nd level)
    // http://www.inregistry.in/policies/reserved_names
);

// ------------------------------
// ccTLD: Japan
// NIC  : http://jprs.co.jp/en/
// Whois: http://whois.jprs.jp/en/
$domain['jp'] = array(
    // Guide to JP Domain Name
    // http://jprs.co.jp/en/jpdomain.html

    // Organizational
    'ac' => true,
    'ad' => true,
    'co' => true,
    'ed' => true,
    'go' => true,
    'gr' => true,
    'lg' => true, // pref.<geographic2nd>.lg.jp etc.
    'ne' => true,
    'or' => true,
    // Geographic
    //
    // Examples for 3rd level domains
    //'kumamoto'  => array(
    //	// http://www.pref.kumamoto.jp/link/list.asp#4
    //	'amakusa'   => TRUE,
    //	'hitoyoshi' => TRUE,
    //	'jonan'     => TRUE,
    //	'kumamoto'  => TRUE,
    //	...
    //),
    'aichi' => true,
    'akita' => true,
    'aomori' => true,
    'chiba' => true,
    'ehime' => true,
    'fukui' => true,
    'fukuoka' => true,
    'fukushima' => true,
    'gifu' => true,
    'gunma' => true,
    'hiroshima' => true,
    'hokkaido' => true,
    'hyogo' => true,
    'ibaraki' => true,
    'ishikawa' => true,
    'iwate' => true,
    'kagawa' => true,
    'kagoshima' => true,
    'kanagawa' => true,
    'kawasaki' => true,
    'kitakyushu' => true,
    'kobe' => true,
    'kochi' => true,
    'kumamoto' => true,
    'kyoto' => true,
    'mie' => true,
    'miyagi' => true,
    'miyazaki' => true,
    'nagano' => true,
    'nagasaki' => true,
    'nagoya' => true,
    'nara' => true,
    'niigata' => true,
    'oita' => true,
    'okayama' => true,
    'okinawa' => true,
    'osaka' => true,
    'saga' => true,
    'saitama' => true,
    'sapporo' => true,
    'sendai' => true,
    'shiga' => true,
    'shimane' => true,
    'shizuoka' => true,
    'tochigi' => true,
    'tokushima' => true,
    'tokyo' => true,
    'tottori' => true,
    'toyama' => true,
    'wakayama' => true,
    'yamagata' => true,
    'yamaguchi' => true,
    'yamanashi' => true,
    'yokohama' => true,
);

// ------------------------------
// ccTLD: South Korea
// NIC  : http://www.nic.or.kr/english/
// Whois: http://whois.nida.or.kr/english/
$domain['kr'] = array(
    // .kr domain policy [appendix 1] : Qualifications for Second Level Domains
    // http://domain.nida.or.kr/eng/policy.jsp

    // Organizational
    'co' => true,
    'ne ' => true,
    'or ' => true,
    're ' => true,
    'pe' => true,
    'go ' => true,
    'mil' => true,
    'ac' => true,
    'hs' => true,
    'ms' => true,
    'es' => true,
    'sc' => true,
    'kg' => true,
    // Geographic
    'seoul' => true,
    'busan' => true,
    'daegu' => true,
    'incheon' => true,
    'gwangju' => true,
    'daejeon' => true,
    'ulsan' => true,
    'gyeonggi' => true,
    'gangwon' => true,
    'chungbuk' => true,
    'chungnam' => true,
    'jeonbuk' => true,
    'jeonnam' => true,
    'gyeongbuk' => true,
    'gyeongnam' => true,
    'jeju' => true,
);

// ------------------------------
// ccTLD: Mexico
// NIC  : http://www.nic.mx/
// Whois: http://www.nic.mx/es/Busqueda.Who_Is
$domain['mx'] = array(
    // Politicas Generales de Nombres de Dominio
    // http://www.nic.mx/es/Politicas?CATEGORY=INDICE
    'com' => true,
    'edu' => true,
    'gob' => true,
    'net' => true,
    'org' => true,
);

// ------------------------------
// ccTLD: New Zealand
// NIC  : http://www.dnc.org.nz/
// Whois: http://www.dnc.org.nz/
$domain['nz'] = array(
    // Second Level Domains
    // http://www.dnc.org.nz/content/second_level_domains.pdf
    'ac' => true,
    'co' => true,
    'gen' => true,
    'geek' => true,
    'maori' => true,
    'net' => true,
    'org' => true,
    'school' => true,
    // policies and procedures: Moderated Second Level Domains
    // http://www.dnc.org.nz/story/30043-35-1.html
    'cri' => true, // Crown Research Institutes
    'govt' => true,
    'iwi' => true, // Traditional Maori tribes
    'mil' => true,
    'parliament' => true,
);

// ------------------------------
// ccTLD: Poland
// NIC  : http://www.dns.pl/english/
// Whois: http://www.dns.pl/cgi-bin/en_whois.pl
$domain['pl'] = array(
    // Functional domain names in NASK
    // http://www.dns.pl/english/dns-funk.html
    'agro' => true,
    'aid' => true,
    'atm' => true,
    'auto' => true,
    'biz' => true,
    'com' => true,
    'edu' => true,
    'gmina' => true,
    'gsm' => true,
    'info' => true,
    'mail' => true,
    'media' => true,
    'miasta' => true,
    'mil' => true,
    'net' => true,
    'nieruchomosci' => true,
    'nom' => true,
    'org' => true,
    'pc' => true,
    'powiat' => true,
    'priv' => true,
    'realestate' => true,
    'rel' => true,
    'sex' => true,
    'shop' => true,
    'sklep' => true,
    'sos' => true,
    'szkola' => true,
    'targi' => true,
    'tm' => true,
    'tourism' => true,
    'travel' => true,
    'turystyka' => true,
    // Regional domain names in NASK
    // http://www.dns.pl/english/dns-regiony.html
    'augustow' => true,
    'babia-gora' => true,
    'bedzin' => true,
    'beskidy' => true,
    'bialowieza' => true,
    'bialystok' => true,
    'bielawa' => true,
    'bieszczady' => true,
    'boleslawiec' => true,
    'bydgoszcz' => true,
    'bytom' => true,
    'cieszyn' => true,
    'czeladz' => true,
    'czest' => true,
    'dlugoleka' => true,
    'elblag' => true,
    'elk' => true,
    'glogow' => true,
    'gniezno' => true,
    'gorlice' => true,
    'grajewo' => true,
    'ilawa' => true,
    'jaworzno' => true,
    'jelenia-gora' => true,
    'jgora' => true,
    'kalisz' => true,
    'karpacz' => true,
    'kartuzy' => true,
    'kaszuby' => true,
    'katowice' => true,
    'kazimierz-dolny' => true,
    'kepno' => true,
    'ketrzyn' => true,
    'klodzko' => true,
    'kobierzyce' => true,
    'kolobrzeg' => true,
    'konin' => true,
    'konskowola' => true,
    'kutno' => true,
    'lapy' => true,
    'lebork' => true,
    'legnica' => true,
    'lezajsk' => true,
    'limanowa' => true,
    'lomza' => true,
    'lowicz' => true,
    'lubin' => true,
    'lukow' => true,
    'malbork' => true,
    'malopolska' => true,
    'mazowsze' => true,
    'mazury' => true,
    'mielec' => true,
    'mielno' => true,
    'mragowo' => true,
    'naklo' => true,
    'nowaruda' => true,
    'nysa' => true,
    'olawa' => true,
    'olecko' => true,
    'olkusz' => true,
    'olsztyn' => true,
    'opoczno' => true,
    'opole' => true,
    'ostroda' => true,
    'ostroleka' => true,
    'ostrowiec' => true,
    'ostrowwlkp' => true,
    'pila' => true,
    'pisz' => true,
    'podhale' => true,
    'podlasie' => true,
    'polkowice' => true,
    'pomorskie' => true,
    'pomorze' => true,
    'prochowice' => true,
    'pruszkow' => true,
    'przeworsk' => true,
    'pulawy' => true,
    'radom' => true,
    'rawa-maz' => true,
    'rybnik' => true,
    'rzeszow' => true,
    'sanok' => true,
    'sejny' => true,
    'siedlce' => true,
    'skoczow' => true,
    'slask' => true,
    'slupsk' => true,
    'sosnowiec' => true,
    'stalowa-wola' => true,
    'starachowice' => true,
    'stargard' => true,
    'suwalki' => true,
    'swidnica' => true,
    'swiebodzin' => true,
    'swinoujscie' => true,
    'szczecin' => true,
    'szczytno' => true,
    'tarnobrzeg' => true,
    'tgory' => true,
    'turek' => true,
    'tychy' => true,
    'ustka' => true,
    'walbrzych' => true,
    'warmia' => true,
    'warszawa' => true,
    'waw' => true,
    'wegrow' => true,
    'wielun' => true,
    'wlocl' => true,
    'wloclawek' => true,
    'wodzislaw' => true,
    'wolomin' => true,
    'wroclaw' => true,
    'zachpomor' => true,
    'zagan' => true,
    'zarow' => true,
    'zgora' => true,
    'zgorzelec' => true,
);

// ------------------------------
// ccTLD: Russia
// NIC  : http://www.cctld.ru/en/
// Whois: http://www.ripn.net:8080/nic/whois/en/
$domain['ru'] = array(
    // List of Reserved second-level Domain Names
    // http://www.cctld.ru/en/doc/detail.php?id21=20&i21=2

    // Organizational
    'ac' => true,
    'com' => true,
    'edu' => true,
    'gov' => true,
    'int' => true,
    'mil' => true,
    'net' => true,
    'org' => true,
    'pp' => true,
    //'test' => TRUE,

    // Geographic
    'adygeya' => true,
    'altai' => true,
    'amur' => true,
    'amursk' => true,
    'arkhangelsk' => true,
    'astrakhan' => true,
    'baikal' => true,
    'bashkiria' => true,
    'belgorod' => true,
    'bir' => true,
    'bryansk' => true,
    'buryatia' => true,
    'cbg' => true,
    'chel' => true,
    'chelyabinsk' => true,
    'chita' => true,
    'chukotka' => true,
    'chuvashia' => true,
    'cmw' => true,
    'dagestan' => true,
    'dudinka' => true,
    'e-burg' => true,
    'fareast' => true,
    'grozny' => true,
    'irkutsk' => true,
    'ivanovo' => true,
    'izhevsk' => true,
    'jamal' => true,
    'jar' => true,
    'joshkar-ola' => true,
    'k-uralsk' => true,
    'kalmykia' => true,
    'kaluga' => true,
    'kamchatka' => true,
    'karelia' => true,
    'kazan' => true,
    'kchr' => true,
    'kemerovo' => true,
    'khabarovsk' => true,
    'khakassia' => true,
    'khv' => true,
    'kirov' => true,
    'kms' => true,
    'koenig' => true,
    'komi' => true,
    'kostroma' => true,
    'krasnoyarsk' => true,
    'kuban' => true,
    'kurgan' => true,
    'kursk' => true,
    'kustanai' => true,
    'kuzbass' => true,
    'lipetsk' => true,
    'magadan' => true,
    'magnitka' => true,
    'mari-el' => true,
    'mari' => true,
    'marine' => true,
    'mordovia' => true,
    'mosreg' => true,
    'msk' => true,
    'murmansk' => true,
    'mytis' => true,
    'nakhodka' => true,
    'nalchik' => true,
    'nkz' => true,
    'nnov' => true,
    'norilsk' => true,
    'nov' => true,
    'novosibirsk' => true,
    'nsk' => true,
    'omsk' => true,
    'orenburg' => true,
    'oryol' => true,
    'oskol' => true,
    'palana' => true,
    'penza' => true,
    'perm' => true,
    'pskov' => true,
    'ptz' => true,
    'pyatigorsk' => true,
    'rnd' => true,
    'rubtsovsk' => true,
    'ryazan' => true,
    'sakhalin' => true,
    'samara' => true,
    'saratov' => true,
    'simbirsk' => true,
    'smolensk' => true,
    'snz' => true,
    'spb' => true,
    'stavropol' => true,
    'stv' => true,
    'surgut' => true,
    'syzran' => true,
    'tambov' => true,
    'tatarstan' => true,
    'tom' => true,
    'tomsk' => true,
    'tsaritsyn' => true,
    'tsk' => true,
    'tula' => true,
    'tuva' => true,
    'tver' => true,
    'tyumen' => true,
    'udm' => true,
    'udmurtia' => true,
    'ulan-ude' => true,
    'vdonsk' => true,
    'vladikavkaz' => true,
    'vladimir' => true,
    'vladivostok' => true,
    'volgograd' => true,
    'vologda' => true,
    'voronezh' => true,
    'vrn' => true,
    'vyatka' => true,
    'yakutia' => true,
    'yamal' => true,
    'yaroslavl' => true,
    'yekaterinburg' => true,
    'yuzhno-sakhalinsk' => true,
    'zgrad' => true,
);

// ------------------------------
// ccTLD: Seychelles
// NIC  : http://www.nic.sc/
// Whois: (Not available)
$domain['sc'] = array(
    // http://www.nic.sc/policies.html
    'com' => true,
    'edu' => true,
    'gov' => true,
    'net' => true,
    'org' => true,
);

// ------------------------------
// ccTLD: Taiwan
// NIC  : http://www.twnic.net.tw/
// Whois: http://www.twnic.net.tw/
$domain['tw'] = array(
    // Guidelines for Administration of Domain Name Registration
    // http://www.twnic.net.tw/english/dn/dn_02.htm
    // II. Types of TWNIC Domain Names and Application Requirements
    // http://www.twnic.net.tw/english/dn/dn_02_b.htm
    'club' => true,
    'com' => true,
    'ebiz' => true,
    'edu' => true,
    'game' => true,
    'gov' => true,
    'idv' => true,
    'mil' => true,
    'net' => true,
    'org' => true,
    // Reserved words for the 2nd level
    // http://mydn.twnic.net.tw/en/dn02/INDEX.htm
);

// ------------------------------
// ccTLD: Tanzania
// NIC  : http://www.psg.com/dns/tz/
// Whois: (Not available)
$domain['tz'] = array(
    //  TZ DOMAIN NAMING STRUCTURE
    // http://www.psg.com/dns/tz/tz.txt
    'ac' => true,
    'co' => true,
    'go' => true,
    'ne' => true,
    'or' => true,
);

// ------------------------------
// ccTLD: Ukraine
// NIC  : http://www.nic.net.ua/
// Whois: http://whois.com.ua/
$domain['ua'] = array(
    // policy for alternative 2nd level domain names (a2ld)
    // http://www.nic.net.ua/doc/a2ld
    // http://whois.com.ua/

    // Organizational
    'com' => true,
    'edu' => true,
    'gov' => true,
    'net' => true,
    'org' => true,
    // Regional (long and short)
    'cherkassy' => true,
    'ck' => true,
    'chernigov' => true,
    'cn' => true,
    'chernovtsy' => true,
    'cv' => true,
    'crimea' => true,
    'cr' => true,
    'dnepropetrovsk' => true,
    'dp' => true,
    'donetsk' => true,
    'dn' => true,
    'ivano-frankivsk' => true,
    'if' => true,
    'kharkov' => true,
    'kh' => true,
    'kherson' => true,
    'ks' => true,
    'khmelnitskiy' => true,
    'km' => true,
    'kiev' => true,
    'kv' => true,
    'kirovograd' => true,
    'kr' => true,
    'lugansk' => true,
    'lg' => true,
    'lutsk' => true,
    'lt' => true,
    'lviv' => true,
    'lv' => true,
    'nikolaev' => true,
    'mk' => true,
    'odessa' => true,
    'od' => true,
    'poltava' => true,
    'pl' => true,
    'rovno' => true,
    'rv' => true,
    'sebastopol' => true,
    'sb' => true,
    'sumy' => true,
    'sm' => true,
    'ternopil' => true,
    'te' => true, // Seems not 'tr'
    'uzhgorod' => true,
    'uz' => true,
    'vinnica' => true,
    'vn' => true,
    'zaporizhzhe' => true,
    'zp' => true,
    'zhitomir' => true,
    'zt' => true,
);

// ------------------------------
// ccTLD: United Kingdom
// NIC  : http://www.nic.uk/
$domain['uk'] = array(
    // Second Level Domains
    // http://www.nic.uk/registrants/aboutdomainnames/sld/
    'co' => true,
    'ltd' => true,
    'me' => true,
    'net' => true,
    'nic' => true,
    'org' => true,
    'plc' => true,
    'sch' => true,
    // Delegated Second Level Domains
    // http://www.nic.uk/registrants/aboutdomainnames/sld/delegated/
    'ac' => true,
    'gov' => true,
    'mil' => true,
    'mod' => true,
    'nhs' => true,
    'police' => true,
);

// ------------------------------
// ccTLD: United States of America
// NIC  : http://nic.us/
// Whois: http://whois.us/
$domain['us'] = array(
    // See RFC1480

    // Organizational
    'dni' => true, // Distributed National Institutes
    'fed' => true, // FEDeral government, <org-name>.<city>.FED.US
    'isa' => true,
    'kids' => true,
    'nsn' => true,
    // Geographical
    // United States Postal Service: State abbreviations (for postal codes)
    // http://www.usps.com/ncsc/lookups/abbreviations.html
    'ak' => true, // Alaska
    'al' => true, // Alabama
    'ar' => true, // Arkansas
    'as' => true, // American samoa
    'az' => true, // Arizona
    'ca' => true, // California
    'co' => true, // Colorado
    'ct' => true, // Connecticut
    'dc' => true, // District of Columbia
    'de' => true, // Delaware
    'fl' => true, // Florida
    'fm' => true, // Federated states of Micronesia
    'ga' => true, // Georgia
    'gu' => true, // Guam
    'hi' => true, // Hawaii
    'ia' => true, // Iowa
    'id' => true, // Idaho
    'il' => true, // Illinois
    'in' => true, // Indiana
    'ks' => true, // Kansas
    'ky' => true, // Kentucky
    'la' => true, // Louisiana
    'ma' => true, // Massachusetts
    'md' => true, // Maryland
    'me' => true, // Maine
    'mh' => true, // Marshall Islands
    'mi' => true, // Michigan
    'mn' => true, // Minnesota
    'mo' => true, // Missouri
    'mp' => true, // Northern mariana islands
    'ms' => true, // Mississippi
    'mt' => true, // Montana
    'nc' => true, // North Carolina
    'nd' => true, // North Dakota
    'ne' => true, // Nebraska
    'nh' => true, // New Hampshire
    'nj' => true, // New Jersey
    'nm' => true, // New Mexico
    'nv' => true, // Nevada
    'ny' => true, // New York
    'oh' => true, // Ohio
    'ok' => true, // Oklahoma
    'or' => true, // Oregon
    'pa' => true, // Pennsylvania
    'pr' => true, // Puerto Rico
    'pw' => true, // Palau
    'ri' => true, // Rhode Island
    'sc' => true, // South Carolina
    'sd' => true, // South Dakota
    'tn' => true, // Tennessee
    'tx' => true, // Texas
    'ut' => true, // Utah
    'va' => true, // Virginia
    'vi' => true, // Virgin Islands
    'vt' => true, // Vermont
    'wa' => true, // Washington
    'wi' => true, // Wisconsin
    'wv' => true, // West Virginia
    'wy' => true, // Wyoming
);

// ------------------------------
// ccTLD: South Africa
// NIC  : http://www.zadna.org.za/
// Whois:
//   ac.za  http://www.tenet.ac.za/cgi/cgi_domainquery.exe
//   co.za  http://co.za/whois.shtml
//   gov.za http://dnsadmin.gov.za/
//   org.za http://www.org.za/
$domain['za'] = array(
    // Second-level subdomains of .ZA
    // http://www.zadna.org.za/slds.html
    'ac' => true,
    'city' => true,
    'co' => true,
    'edu' => true,
    'gov' => true,
    'law' => true,
    'mil' => true,
    'nom' => true,
    'org' => true,
    'school' => array(
        // Provincial Domains
        // http://www.esn.org.za/dns/
        'ecape' => true, // Eastern Cape
        'fs.' => true, // Free State
        'gp' => true, // Gauteng Province
        'kzn' => true, // Kwazulu-Natal
        'lp' => true, // Limpopo Province
        'mpm' => true, // Mpumalanga
        'ncape' => true, // Northern Cape
        'nw' => true, // North-West Province
        'wcape' => true, // Western Cape
    ),
);
