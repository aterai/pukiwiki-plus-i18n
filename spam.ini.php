<?php
// $Id: spam.ini.php,v 1.80 2007/09/24 16:01:00 henoheno Exp $
// Spam-related setting

// NOTE FOR ADMINISTRATORS:
//
// Host selection:
//   [1] '.example.org'  prohibits ALL "example.org"-related FQDN
//   [2] '*.example.org' prohibits ONLY subdomains and hosts, EXCEPT "www.example.org"
//   [3] 'example.org'   prohibits BOTH "example.org" and "www.example.org"
//   (Now you know, [1] = [2] + [3])
//
// How to write multiple hosts as an group:
//  'Group Name' => array('a.example.org', 'b.example.com', 'c.example.net'),
//
// How to write regular expression:
//  'Group Name' => '#^(?:.*\.)?what-you-want\.com$#',
//
// Guideline to keep group names unique:
//   - Using capitalized letters, spaces, commas (etc) may suggest you
//     that probably be a group.
//   - Unique word examples:
//     [1] FQDN
//     [2] Mail address of the domain-name owner
//     [3] IP address, if these hosts have the same ones
//     [4] Something unique idea of you
//
// Reference:
//   http://en.wikipedia.org/wiki/Spamdexing
//   http://en.wikipedia.org/wiki/Domainers
//   http://en.wikipedia.org/wiki/Typosquatting


// --------------------------------------------------
// List of the lists

//  FALSE	= ignore them
//  TRUE	= catch them
//  Commented out of the line = do nothing about it

// 'pre': Before the other filters/checkers
$blocklist['pre'] = array(
	'goodhost'	=> FALSE,
);

// 'list': Normal list
$blocklist['list'] = array(
	'A-1'		=> TRUE,	// General redirection services
	//'A-2'		=> TRUE,	// Dynamic DNS, Dynamic IP services, ...
	'B-1'		=> TRUE,	// Web spaces
	'B-2'		=> TRUE,	// Jacked contents, something implanted
	'C'			=> TRUE,	// Exclusive spam domains
	//'D'		=> TRUE,	// "Third party in good faith"s
	'E'			=> TRUE,	// Affiliates, Hypes, Catalog retailers, Multi-level marketings, ...
	'Z'			=> TRUE,	// Yours
);


// --------------------------------------------------

$blocklist['goodhost'] = array(
	// Sample setting of ignorance list

	'IANA-examples' => '#^(?:.*\.)?example\.(?:com|net|org)$#',

	// PukiWiki-official/dev specific
	//'pukiwiki.sourceforge.jp',
	//'pukiwiki.org',	// Temporary
	//'.logue.tk',	// Well-known PukiWiki heavy user, Logue (Paid *.tk domain, Expire on 2008-12-01)
	//'.nyaa.tk',	// (Paid *.tk domain, Expire on 2008-05-19)
	//'.wanwan.tk',	// (Paid *.tk domain, Expire on 2008-04-21) by nyaa.tk
	//'emasaka.blog65.fc2.com',	// Text-to-Impress converter
	//'ifastnet.com',				// Server hosting
	//'threefortune.ifastnet.com',	// Server hosting
    'www.javaspecialists.eu'

	// Yours
	//''
	//''
	//''

);

// --------------------------------------------------
// A: Sample setting of
// Existing URI redirection or masking services

$blocklist['A-1'] = array(

	// A-1: General redirection services -- by HTML meta, HTML frame, JavaScript,
	// web-based proxy, DNS subdomains, etc
	// http://en.wikipedia.org/wiki/URL_redirection
	//
	// as known as cheap URI obscuring services today,
	// for spammers and affiliate users dazed by money.
	//
	//   Messages from forerunners:
	//     o-rly.net
	//       "A URL REDIRECTION SERVICE GONE BAD"
	//       "SORRY, TRULY"
	//     smcurl.com
	//       "Idiots were using smcURL to shrink URLs and
	//        send them out via spam."
	//     tinyclick.com
	//       "...stop offering it's free services because
	//        too many people were taking advantage of it"
	//     xjs.org
	//       "We have been forced to close this facility
	//        due to a minority of knuckle draggers who
	//        abused this web site."
	//
	// Please notify us about this list with reason:
	// http://pukiwiki.sourceforge.jp/dev/?BugTrack2/207
	//
	'0nz.org',
	'0rz.tw',
	'0url.com',
	'0zed.info',
	'*.110mb.com',	// by Speed Success, Inc. (110mb.server at gmail.com)
	'123.que.jp',
	'12url.org',
	'*.15h.com',
	'*.1dr.biz',
	'1K.pl' => array(
		'*.1k.pl',
		'*.5g.pl',
		'*.orq.pl',
	),
	'1nk.us',
	'1url.org',
	'1url.in',
	'1webspace.org',
	'2Ch.net' => array(
		'ime.nu',
		'ime.st',
	),
	'2ch2.net',
	'2hop4.com',
	'2s.ca',
	'2site.com',
	'2url.org',
	'301url.com',
	'32url.com',
	'.3dg.de',
	'*.4bb.ru',
	'big5.51job.com',	///gate/big5/
	'5jp.net',
	'.6url.com',
	'*.6x.to',
	'7ref.com',
	'82m.org',
	'*.8rf.com',
	'98.to',
	'abbrv.co.uk',
	'*.abwb.org',
	'acnw.de',
	'Active.ws' => array(
		'*.321.cn',
		'*.4x2.net',
		'active.ws',
		'*.better.ws',
		'*.here.ws',
		'*.mypiece.com',
		'*.official.ws',
		'*.ouch.ws',
		'*.premium.ws',
		'*.such.info',
		'*.true.ws',
		'*.visit.ws',
	),
	'affilitool.com',		// 125.206.117.91(right-way.org) by noboru hamada (info at isosupport.net)
	'aifam.com',
	'All4WebMasters.pl' => array(
		'*.ovp.pl',
		'*.6-6-6.pl',
	),
	'amoo.org',
	'web.archive.org',		///web/2
	'Arzy.net' => array(	// "(c) 2007 www.arzy.net", by urladmin at zvxr.com, DNS arzy.net
		'jmp2.net',
		'2me.tw',
	),
	'ataja.es',
	'ATBHost.com' => array(
		'*.atbhost.com',
		'*.bzhost.net',
	),
	'atk.jp',
	'clearp.ath.cx',
	'athomebiz.com',
	'aukcje1.pl',
	'beam.to',
	'beermapping.com',
	'besturl.in',
	'bhomiyo.com',		///en.xliterate/ 64.209.134.9(web137.discountasp.net) by piyush at arborindia.com
	'biglnk.com',
	'bingr.com',
	'bittyurl.com',
	'*.bizz.cc',
	'*.blo.pl',
	'*.bo.pl',
	'briefurl.com',
	'brokenscript.com',
	'BucksoGen.com' => array(
		'*.bucksogen.com',
		'*.bulochka.org',
		'*.korzhik.org',
		'*.kovrizhka.org',
		'*.pirozhok.org',
		'*.plushka.org',
		'*.pryanik.org',
		'*.sushka.org',
	),
	'budgethosts.org',
	'budu.com',				// by peter.eder at imcworld.com
	'*.buzznet.com',
	'*.bydl.com',
	'C-O.IN' => array(
		'*.c-o.cc',
		'*.c-o.in',
		'*.coz.in',
		'*.cq.bz',
	),
	'c64.ch',
	'c711.com',
	'*.cej.pl',
	'checkasite.net',
	'url.chefhost.com',
	'*.chicappa.jp',
	'chilicity.com',
	'big5.china.com',		///gate/big5/
	'chopurl.com',
	'christopherleestreet.com',
	'cintcm.com',
	'*.cjb.net',
	'clipurl.com',
	'*.co.nr',
	'Comtech Enterprises ' => array(	// comteche.com
		'tinyurl.name',
		'tinyurl.us',
	),
	'Cool168.com' => array(
		'*.cool158.com',
		'*.cool168.com',
		'*.ko168.com',
		'*.ko188.com',
	),
	'Coolurl.de' => array(
		'coolurl.de',
		'dornenboy.de',
		'eyeqweb.com',
		'hardcore-porn.de',
		'maschinen-bluten-nicht.de',
	),
	'cutalink.com',
	'*.da.cx',
	'*.da.ru',
	'dae2.com',
	'dephine.org',
	'desiurl.com',
	'dhurl.com',
	'digbig.com',
	'Digipills.com' => array(
		'*.digipills.com',
		'minilien.com',
		'tinylink.com',
	),
	'*.discutbb.com',
	'DL.AM' => array(
		'*.cx.la',
		'*.dl.am',
	),
	'*.dl.pl',
	'*.dmdns.com',
	'doiop.com',
	'drlinky.com',
	'durl.us',
	'*.dvdonly.ru',
	'*.dynu.ca',
	'dwarf.name',
	'*.eadf.com',
	'*.easyurl.net',
	'easyurl.jp',	// 124.38.169.39(*.ap124.ftth.ucom.ne.jp), e-mail:info at value-domain.com,
		// says "by ascentnet.co.jp". http://www.ascentnet.co.jp/press/?type=1&press=45
		// This service seems to be opened at 2007/08/23 with "beta" sign.
		// easyurl.jp clearly point ascentnet.co.jp's 10 local rules:
		//   "Keep continuing to seek originality and contribute it to local,
		//    get/grow niche brands (in local), believe (local) people knows the answer,
		//    observe (local) rule, create nothing to infringe (local) rule, keep 70% of
		//    engeneers, and ..." http://www.ascentnet.co.jp/about/about_01.html
		// I'm so much impressed of the situation around this imported one today.
	'elfurl.com',
	'eny.pl',
	'eTechFocus LLC' => array(	// by eTechFocus LLC (thomask at etechfocus.com)
		'.mywiitime.com',
		'.surfindark.com',		// webmaster at etechfocus.com
		'.surfinshade.com',
		'.surfinshadow.com',
		'.surfinwind.com',
		'.topsecretlive.com',
	),
	'*.eu.org',
	'F2B.be' => array(
		'*.f2b.be',
		'*.freakz.eu',
		'*.n0.be',
		'*.n3t.nl',
		'*.short.be',
		'*.ssr.be',
		'*.tweaker.eu',
	),
	'*.fancyurl.com',
	'Fanznet.jp' => array(	// by takahashi nakaba (nakaba.takahashi at gmail.com)
		'blue11.jp',
		'fanznet.com',
		'katou.in',
		'mymap.in',
		'saitou.in',
		'satou.in',
		'susan.in',
	),
	'.fe.pl',			// Redirection and subdomain
	'ffwd.to',
	'url.fibiger.org',
	'FireMe.to' => array(
		'fireme.to',
		'nextdoor.to',
		'ontheway.to',
	),
	'flingk.com',
	'flog.jp',			// careless redirector and bbs
	'fm7.biz',
	'fnbi.jp',
	'*.fnbi.jp',
	'forgeturl.com',
	'*.free.bg',
	'Freeservers.com' => array(	// United Online Web Services, Inc.
		'*.4mg.com',
		'*.4t.com',
		'*.8m.com',
		'*.8m.net',
		'*.8k.com',
		'*.faithweb.com',
		'*.freehosting.net',
		'*.freeservers.com',
		'*.gq.nu',
		'*.htmlplanet.com',
		'*.itgo.com',
		'*.iwarp.com',
		'*.s5.com',
		'*.scriptmania.com',
		'*.tvheaven.com',
	),
	'*.freewebpages.com',
	'FreeWebServices.net' => array(	// Host Department LLC
		'*.about.gs',	// Dead?
		'*.about.tc',
		'*.about.vg',
		'*.aboutus.gs',
		'*.aboutus.ms',
		'*.aboutus.tc',
		'*.aboutus.vg',
		'*.biografi.biz',
		'*.biografi.info',
		'*.biografi.org',
		'*.biografi.us',
		'*.datadiri.biz',
		'*.datadiri.cc',
		'*.datadiri.com',
		'*.datadiri.info',
		'*.datadiri.net',
		'*.datadiri.org',
		'*.datadiri.tv',
		'*.datadiri.us',
		'*.ecv.gs',
		'*.ecv.ms',
		'*.ecv.tc',
		'*.ecv.vg',
		'*.eprofile.us',
		'*.go2net.ws',
		'*.hits.io',
		'*.hostingweb.us',
		'*.hub.io',
		'*.indo.bz',
		'*.indo.cc',
		'*.indo.gs',
		'*.indo.ms',
		'*.indo.tc',
		'*.indo.vg',
		'*.infinitehosting.net',
		'*.infinites.net',
		'*.lan.io',
		'*.max.io',
		'*.mycv.bz',
		'*.mycv.nu',
		'*.mycv.tv',
		'*.myweb.io',
		'*.ourprofile.biz',
		'*.ourprofile.info',
		'*.ourprofile.net',	// Dead?
		'*.ourprofile.org',
		'*.ourprofile.us',
		'*.profil.bz',
		'*.profil.cc',
		'*.profil.cn',
		'*.profil.gs',
		'*.profil.in',
		'*.profil.ms',
		'*.profil.tc',
		'*.profil.tv',
		'*.profil.vg',	// ?
		'*.site.io',
		'*.wan.io',
		'*.web-cam.ws',
		'*.webs.io',
		'*.zip.io',
	),
	'funkurl.com',		// by Leonard Lyle (len at ballandchain.net)
	'*.fx.to',
	'fyad.org',
	'fype.com',
	'gentleurl.net',
	'Get2.us' => array(
		'*.get2.us',
		'*.hasballs.com',
		'*.ismyidol.com',
		'*.spotted.us',
		'*.went2.us',
		'*.wentto.us',
	),
	'glinki.com',
	'*.globalredirect.com',
	'gnu.vu',
	'*.go.cc',
	//'Google.com' => array(
	//		google.com/translate_c\?u=(?:http://)?
	//),
	'goonlink.com',
	'.gourl.org',
	'.greatitem.com',
	'gzurl.com',
	'url.grillsportverein.de',
	'Harudake.net' => array('*.hyu.jp'),
	'Hattinger Linux User Group' => array('short.hatlug.de'),
	'Hexten.net' => array('lyxus.net'),
	'here.is',
	'HispaVista.com' => array(
		'*.blogdiario.com',
		'*.hispavista.com',
		'.galeon.com',
	),
	'Home.pl' => array(	// by Home.pl Sp. J. (info at home.pl), redirections and forums
		'*.8l.pl',
		'*.blg.pl',
		'*.czytajto.pl',
		'*.ryj.pl',
		'*.xit.pl',
		'*.xlc.pl',
		'*.hk.pl',
		'*.home.pl',
		'*.of.pl',
	),
	'hort.net',
	'free4.hostrocket.com',
	'*.hotindex.ru',
	'HotRedirect.com' => array(
		'*.coolhere.com',
		'*.homepagehere.com',
		'*.hothere.com',
		'*.mustbehere.com',
		'*.onlyhere.net',
		'*.pagehere.com',
		'*.surfhere.net',
		'*.zonehere.com',
	),
	'hotshorturl.com',
	'hotwebcomics.com',	///search_redirect.php
	'hurl.to',
	'*.hux.de',
	'*.i89.us',
	'iat.net',			// 74.208.58.130 by Tony Carter
	'ibm.com',			///links (Correct it)
	'*.iceglow.com',
	'go.id-tv.info',	// 77.232.68.138(77-232-68-138.static.servage.net) by Max Million (max at id-tv.info)
	'Ideas para Nuevos Mercados SL' => array(
		// NOTE: 'i4nm.com' by 'Ideas para Nuevos Mercados SL' (i4nm at i4nm.com)
		// NOTE: 'dominiosfree.com' by 'Ideas para nuevos mercados,sl' (dominiosfree at i4nm.com)
		// NOTE: 'red-es.com' by oscar florez (info at i4nm.com)
		// by edgar bortolin (oscar at i4nm.com)
		// by Edgar Bortolin  (oscar at i4nm.com)
		// by oscar florez (oscar at i4nm.com)
		// by Oscar Florez (oscar at red-es.com)
		// by covadonga del valle (oscar at i4nm.com)
		'*.ar.gd',
		'*.ar.gs',	// ns *.nora.net
		'*.ar.kz',	// by oscar
		'*.ar.nu',	// by Edgar
		'*.ar.tc',	// by oscar
		'*.ar.vg',	// by oscar
		'*.bo.kz',	// by oscar
		'*.bo.nu',	// by covadonga
		'*.bo.tc',	// by oscar
		'*.bo.tf',	// by Oscar
		'*.bo.vg',	// by oscar
		'*.br.gd',
		'*.br.gs',	// ns *.nora.net
		'*.br.nu',	// by edgar
		'*.br.vg',	// by oscar
		'*.ca.gs',	// by oscar
		'*.ca.kz',	// by oscar
		'*.cl.gd',	// by oscar
		'*.cl.kz',	// by oscar
		'*.cl.nu',	// by edgar
		'*.cl.tc',	// by oscar
		'*.cl.tf',	// by Oscar
		'*.cl.vg',	// by oscar
		'*.col.nu',	// by Edgar
		'*.cr.gs',	// ns *.nora.net
		'*.cr.kz',	// by oscar
		'*.cr.nu',	// by edgar
		'*.cr.tc',	// by oscar
		'*.cu.tc',	// by oscar
		'*.do.kz',	// by oscar
		'*.do.nu',	// by edgar
		'*.ec.kz',	// by edgar
		'*.ec.nu',	// by Edgar
		'*.ec.tf',	// by Oscar
		'*.es.kz',	// by oscar
		'*.eu.kz',	// by oscar
		'*.gt.gs',	// ns *.nora.net
		'*.gt.tc',	// by oscar
		'*.gt.tf',	// by Oscar
		'*.gt.vg',	// by Oscar
		'*.hn.gs',	// ns *.nora.net
		'*.hn.tc',	// by oscar
		'*.hn.tf',	// by Oscar
		'*.hn.vg',	// by oscar
		'*.mx.gd',
		'*.mx.gs',	// ns *.nora.net
		'*.mx.kz',	// by oscar
		'*.mx.vg',	// by oscar
		'*.ni.kz',	// by oscar
		'*.pa.kz',	// by oscar
		'*.pe.kz',	// by oscar
		'*.pe.nu',	// by Edgar
		'*.pr.kz',	// by oscar
		'*.pr.nu',	// by edgar
		'*.pt.gs',	// ns *.nora.net
		'*.pt.kz',	// by edgar
		'*.pt.nu',	// by edgar
		'*.pt.tc',	// by oscar
		'*.pt.tf',	// by Oscar
		'*.py.gs',	// ns *.nora.net
		'*.py.nu',	// by edgar
		'*.py.tc',	// by oscar
		'*.py.tf',	// by Oscar
		'*.py.vg',	// by oscar
		'*.sv.tc',	// by oscar
		'*.usa.gs',	// ns *.nora.net
		'*.uy.gs',	// ns *.nora.net
		'*.uy.kz',	// by oscar
		'*.uy.nu',	// by edgar
		'*.uy.tc',	// by oscar
		'*.uy.tf',	// by Oscar
		'*.uy.vg',	// by oscar
		'*.ve.gs',	// by oscar
		'*.ve.tc',	// by oscar
		'*.ve.tf',	// by Oscar
		'*.ve.vg',	// by oscar
		'*.ven.nu',	// by edgar
	),
	'ie.to',
	'igoto.co.uk',
	'ilook.tw',
	'indianpad.com',		///view/
	'iNetwork.co.il' => array(
		'inetwork.co.il',	// by NiL HeMo (exe at bezeqint.net)
		'.up2.co.il',		// inetwork.co.il related, not classifiable, by roey blumshtein (roeyb76 at 017.net.il)
		'.dcn.co.il,',		// up2.co.il related, not classifiable, by daniel chechik (ns_daniel0 at bezeqint.net)
	),
	'*.infogami.com',
	'infotop.jp',
	'ipoo.org',
	'IR.pl' => array(
		'*.aj.pl',
		'*.aliasy.org',
		'*.gu.pl',
		'*.hu.pl',
		'*.ir.pl',
		'*.jo.pl',
		'*.su.pl',
		'*.td.pl',
		'*.uk.pl',
		'*.uy.pl',
		'*.xa.pl',
		'*.zj.pl',
	),
	'irotator.com',
	'.iwebtool.com',
	'j6.bz',
	'jeeee.net',
	'Jaze Redirect Services' => array(
		'*.arecool.net',
		'*.iscool.net',
		'*.isfun.net',
		'*.tux.nu',
	),
	'*.jed.pl',
	'JeremyJohnstone.com' => array('url.vg'),
	'jemurl.com',
	'jggj.net',
	'jpan.jp',
	'josh.nu',
	'kat.cc',
	'Kickme.to' => array(
		'.1024bit.at',
		'.128bit.at',
		'.16bit.at',
		'.256bit.at',
		'.32bit.at',
		'.512bit.at',
		'.64bit.at',
		'.8bit.at',
		'.adores.it',
		'.again.at',
		'.allday.at',
		'.alone.at',
		'.altair.at',
		'.american.at',
		'.amiga500.at',
		'.ammo.at',
		'.amplifier.at',
		'.amstrad.at',
		'.anglican.at',
		'.angry.at',
		'.around.at',
		'.arrange.at',
		'.australian.at',
		'.baptist.at',
		'.basque.at',
		'.battle.at',
		'.bazooka.at',
		'.berber.at',
		'.blackhole.at',
		'.booze.at',
		'.bosnian.at',
		'.brainiac.at',
		'.brazilian.at',
		'.bummer.at',
		'.burn.at',
		'.c-64.at',
		'.catalonian.at',
		'.catholic.at',
		'.chapel.at',
		'.chills.it',
		'.christiandemocrats.at',
		'.cname.at',
		'.colors.at',
		'.commodore.at',
		'.commodore64.at',
		'.communists.at',
		'.conservatives.at',
		'.conspiracy.at',
		'.cooldude.at',
		'.craves.it',
		'.croatian.at',
		'.cuteboy.at',
		'.dancemix.at',
		'.danceparty.at',
		'.dances.it',
		'.danish.at',
		'.dealing.at',
		'.deep.at',
		'.democrats.at',
		'.digs.it',
		'.divxlinks.at',
		'.divxmovies.at',
		'.divxstuff.at',
		'.dizzy.at',
		'.does.it',
		'.dork.at',
		'.drives.it',
		'.dutch.at',
		'.dvdlinks.at',
		'.dvdmovies.at',
		'.dvdstuff.at',
		'.emulators.at',
		'.end.at',
		'.english.at',
		'.eniac.at',
		'.error403.at',
		'.error404.at',
		'.evangelism.at',
		'.exhibitionist.at',
		'.faith.at',
		'.fight.at',
		'.finish.at',
		'.finnish.at',
		'.forward.at',
		'.freebie.at',
		'.freemp3.at',
		'.french.at',
		'.graduatejobs.at',
		'.greenparty.at',
		'.grunge.at',
		'.hacked.at',
		'.hang.at',
		'.hangup.at',
		'.has.it',
		'.hide.at',
		'.hindu.at',
		'.htmlpage.at',
		'.hungarian.at',
		'.icelandic.at',
		'.independents.at',
		'.invisible.at',
		'.is-chillin.it',
		'.is-groovin.it',
		'.japanese.at',
		'.jive.at',
		'.kickass.at',
		'.kickme.to',
		'.kindergarden.at',
		'.knows.it',
		'.kurd.at',
		'.labour.at',
		'.leech.at',
		'.liberals.at',
		'.linuxserver.at',
		'.liqour.at',
		'.lovez.it',
		'.makes.it',
		'.maxed.at',
		'.means.it',
		'.meltdown.at',
		'.methodist.at',
		'.microcomputers.at',
		'.mingle.at',
		'.mirror.at',
		'.moan.at',
		'.mormons.at',
		'.musicmix.at',
		'.nationalists.at',
		'.needz.it',
		'.nerds.at',
		'.neuromancer.at',
		'.newbie.at',
		'.nicepage.at',
		'.ninja.at',
		'.norwegian.at',
		'.ntserver.at',
		'.owns.it',
		'.paint.at',
		'.palestinian.at',
		'.phoneme.at',
		'.phreaking.at',
		'.playz.it',
		'.polish.at',
		'.popmusic.at',
		'.portuguese.at',
		'.powermac.at',
		'.processor.at',
		'.prospects.at',
		'.protestant.at',
		'.rapmusic.at',
		'.raveparty.at',
		'.reachme.at',
		'.reads.it',
		'.reboot.at',
		'.relaxed.at',
		'.republicans.at',
		'.researcher.at',
		'.reset.at',
		'.resolve.at',
		'.retrocomputers.at',
		'.rockparty.at',
		'.rocks.it',
		'.rollover.at',
		'.rough.at',
		'.rules.it',
		'.rumble.at',
		'.russian.at',
		'.says.it',
		'.scared.at',
		'.seikh.at',
		'.serbian.at',
		'.short.as',
		'.shows.it',
		'.silence.at',
		'.simpler.at',
		'.sinclair.at',
		'.singz.it',
		'.slowdown.at',
		'.socialists.at',
		'.spanish.at',
		'.split.at',
		'.stand.at',
		'.stoned.at',
		'.stumble.at',
		'.supercomputer.at',
		'.surfs.it',
		'.swedish.at',
		'.swims.it',
		'.synagogue.at',
		'.syntax.at',
		'.syntaxerror.at',
		'.techie.at',
		'.temple.at',
		'.thinkbig.at',
		'.thirsty.at',
		'.throw.at',
		'.toplist.at',
		'.trekkie.at',
		'.trouble.at',
		'.turkish.at',
		'.unexplained.at',
		'.unixserver.at',
		'.vegetarian.at',
		'.venture.at',
		'.verycool.at',
		'.vic-20.at',
		'.viewing.at',
		'.vintagecomputers.at',
		'.virii.at',
		'.vodka.at',
		'.wannabe.at',
		'.webpagedesign.at',
		'.wheels.at',
		'.whisper.at',
		'.whiz.at',
		'.wonderful.at',
		'.zor.org',
		'.zx80.at',
		'.zx81.at',
		'.zxspectrum.at',
	),
	'kisaweb.com',
	'krotki.pl',
	'kuerzer.de',
	'*.kupisz.pl',
	'kuso.cc',
	'*.l8t.com',
	'lame.name',
	'lediga.st',
	'liencourt.com',
	'liteurl.com',
	'linkachi.com',
	'linkezy.com',
	'linkfrog.net',
	'linkook.com',
	'linkzip.net',
	'lispurl.com',
	'lnk.in',
	'makeashorterlink.com',
	'MAX.ST' => array(	// by Guet Olivier (oliguet at club-internet.fr), frame
		'*.3gp.fr',
		'*.gtx.fr',
		'*.ici.st',
		'*.max.st',
		'*.nn.cx',		// ns *.sivit.org
		'*.site.cx',	// ns *.sivit.org
		'*.user.fr',
		'*.zxr.fr',
	),
	'mcturl.com',
	'memurl.com',
	'Metamark.net' => array('xrl.us'),
	'midgeturl.com',
	'Minilink.org' => array('lnk.nu'),
	'miniurl.org',
	'miniurl.pl',
	'mixi.bz',
	'mo-v.jp',
	'MoldData.md' => array(	// Note: Some parts of '.md' ccTLD
		'.com.md',
		'.co.md',
		'.org.md',
		'.info.md',
		'.host.md',
	),
	'monster-submit.com',
	'mooo.jp',
	'murl.net',
	'myactivesurf.net',
	'mytinylink.com',
	'myurl.in',
	'myurl.com.tw',
	'nanoref.com',
	'Ne1.net' => array(
		'*.ne1.net',
		'*.r8.org',
	),
	'Nashville Linux Users Group' => array('nlug.org'),
	'not2long.net',
	'*.notlong.com',
	'*.nuv.pl',
	'ofzo.be',
	'*.ontheinter.net',
	'ourl.org',
	'ov2.net',				// frame
	'*.ozonez.com',
	'pagebang.com',
	'palurl.com',
	'*.paulding.net',
	'phpfaber.org',
	'pnope.com',
	'prettylink.com',
	'PROXID.net' => array(	// also xRelay.net
		'*.asso.ws',
		'*.corp.st',
		'*.euro.tm',
		'*.perso.tc',
		'*.site.tc',
		'*.societe.st',
	),
	'qrl.jp',
	'qurl.net',
	'qwer.org',
	'readthisurl.com',		// 67.15.58.36(win2k3.tuserver.com) by Zhe Hong Lim (zhehonglim at gmail.com)
	'radiobase.net',
	'Rakuten.co.jp' => array(
		'pt.afl.rakuten.co.jp',	///c/
	),
	'RedirectFree.com' => array(
		'*.red.tc',
		'*.redirectfree.com',
		'*.sky.tc',
		'*.the.vg',
	),
	'redirme.com',
	'redirectme.to',
	'relic.net',
	'rezma.info',
	'rio.st',
	'rlink.org',
	'*.rmcinfo.fr',
	'rubyurl.com',
	'*.runboard.com',
	'runurl.com',
	's-url.net',
	's1u.net',
	'SG5.co.uk' => array(
		'*.sg5.co.uk',
		'*.sg5.info',
	),
	'Shim.net' => array(
		'*.0kn.com',
		'*.2cd.net',
		'*.freebiefinders.net',
		'*.freegaming.org',
		'*.op7.net',
		'*.shim.net',
		'*.v9z.com',
	),
	'big5.shippingchina.com',
	'shorl.com',
	'shortenurl.com',
	'shorterlink.com',
	'shortlinks.co.uk',
	'shorttext.com',
	'shorturl-accessanalyzer.com',
	'Shortify.com' => array(
		'74678439.com',
		'shortify.com',
	),
	'shortlink.co.uk',
	'ShortURL.com' => array(
		'*.1sta.com',
		'*.24ex.com',
		'*.2fear.com',
		'*.2fortune.com',
		'*.2freedom.com',
		'*.2hell.com',
		'*.2savvy.com',
		'*.2truth.com',
		'*.2tunes.com',
		'*.2ya.com',
		'*.alturl.com',
		'*.antiblog.com',
		'*.bigbig.com',
		'*.dealtap.com',
		'*.ebored.com',
		'*.echoz.com',
		'*.filetap.com',
		'*.funurl.com',
		'*.headplug.com',
		'*.hereweb.com',
		'*.hitart.com',
		'*.mirrorz.com',
		'*.shorturl.com',
		'*.spyw.com',
		'*.vze.com',
	),
	'shrinkalink.com',
	'shrinkthatlink.com',
	'shrinkurl.us',
	'shrt.org',
	'shrunkurl.com',
	'shurl.org',
	'shurl.net',
	'sid.to',
	'simurl.com',
	'sitefwd.com',
	'Sitelutions.com' => array(
		'*.assexy.as',
		'*.athersite.com',
		'*.athissite.com',
		'*.bestdeals.at',
		'*.byinter.net',
		'*.findhere.org',
		'*.fw.nu',
		'*.isgre.at',
		'*.isthebe.st',
		'*.kwik.to',
		'*.lookin.at',
		'*.lowestprices.at',
		'*.onthenet.as',
		'*.ontheweb.nu',
		'*.pass.as',
		'*.passingg.as',
		'*.redirect.hm',
		'*.rr.nu',
		'*.ugly.as',
	),
	'*.skracaj.pl',
	'skiltechurl.com',
	'skocz.pl',
	'slimurl.jp',
	'slink.in',
	'smallurl.eu',
	'smurl.name',
	'snipurl.com',
	'sp-nov.net',
	'splashblog.com',
	'spod.cx',
	'*.spydar.com',
	'Subdomain.gr' => array(
		'*.p2p.gr',
		'*.subdomain.gr',
	),
	'SURL.DK' => array('surl.dk'),	// main page is: s-url.dk
	'surl.se',
	'surl.ws',
	'symy.jp',
	'tdurl.com',
	'tighturl.com',
	'tiniuri.com',
	'tiny.cc',
	'tiny.pl',
	'tiny2go.com',
	'tinylink.eu',
	'tinylinkworld.com',
	'tinypic.com',
	'tinyr.us',
	'TinyURL.com' => array(
		'tinyurl.com',
		'preview.tinyurl.com',
		'tinyurl.co.uk',
	),
	'titlien.com',
	'*.tlg.pl',
	'tlurl.com',
	'link.toolbot.com',
	'tnij.org',
	'Tokelau ccTLD' => array('.tk'),
	'toila.net',
	'*.toolbot.com',
	'*.torontonian.com',
	'trimurl.com',
	'ttu.cc',
	'turl.jp',
	'*.tz4.com',
	'U.TO' => array(	// ns *.1004web.com, 1004web.com is owned by Moon Jae Bark (utomaster at gmail.com) = u.to master
		'*.1.to',
		'*.4.to',
		'*.5.to',
		'*.82.to',
		'*.s.to',
		'*.u.to',
		'*.ce.to',
		'*.cz.to',
		'*.if.to',
		'*.it.to',
		'*.kp.to',
		'*.ne.to',
		'*.ok.to',
		'*.pc.to',
		'*.tv.to',
		'*.dd.to',
		'*.ee.to',
		'*.hh.to',
		'*.kk.to',
		'*.mm.to',
		'*.qq.to',
		'*.xx.to',
		'*.zz.to',
		'*.ivy.to',
		'*.joa.to',
		'*.ever.to',
		'*.mini.to',
	),
	'uchinoko.in',
	'Ulimit.com' => array(
		'*.be.tf',
		'*.best.cd',
		'*.bsd-fan.com',
		'*.c0m.st',
		'*.ca.tc',
		'*.clan.st',
		'*.com02.com',
		'*.en.st',
		'*.euro.st',
		'*.fr.fm',
		'*.fr.st',
		'*.fr.vu',
		'*.gr.st',
		'*.ht.st',
		'*.int.ms',
		'*.it.st',
		'*.java-fan.com',
		'*.linux-fan.com',
		'*.mac-fan.com',
		'*.mp3.ms',
		'*.qc.tc',
		'*.sp.st',
		'*.suisse.st',
		'*.t2u.com',
		'*.unixlover.com',
		'*.zik.mu',
	),
	'*.uni.cc',
	'UNONIC.com' => array(
		'*.at.tf',	// AlpenNIC
		'*.bg.tf',
		'*.ca.tf',
		'*.ch.tf',	// AlpenNIC
		'*.cz.tf',
		'*.de.tf',	// AlpenNIC
		'*.edu.tf',
		'*.eu.tf',
		'*.int.tf',
		'*.net.tf',
		'*.pl.tf',
		'*.ru.tf',
		'*.sg.tf',
		'*.us.tf',
	),
	'Up.pl' => array(
		'.69.pl',			// by nsk101869
		'.crack.pl',		// by nsk101869
		'.film.pl',			// by sibr19002
		'.h2o.pl',			// by nsk101869
		'.hostessy.pl',		// by nsk101869
		'.komis.pl',		// by nsk101869
		'.laski.pl',		// by nsk101869
		'.modelki.pl',		// by nsk101869
		'.muzyka.pl',		// by sibr19002
		'.nastolatki.pl',	// by nsk101869
		'.obuwie.pl',		// by nsk101869
		'.prezes.com',		// by Robert e (b2b at interia.pl)
		'.prokuratura.com',	// by Robert Tofil (b2b at interia.pl)
		'.sexchat.pl',		// by nsk101869
		'.sexlive.pl',		// by nsk101869
		'.tv.pl',			// by nsk101869
		'.up.pl',			// by nsk101869
		'.video.pl',		// by nsk101869
		'.xp.pl',			// nsk101869
	),
	'*.uploadr.com',
	'url.ie',
	'url4.net',
	'url-c.com',
	'urlbee.com',
	'urlbounce.com',
	'urlcut.com',
	'urlcutter.com',
	'urlic.com',
	'urlin.it',
	'urlkick.com',
	'URLLogs.com' => array(
		'*.urllogs.com',	// 67.15.219.253 by Javier Keeth (abuzant at gmail.com), ns *.pengs.com, 'Hosted by: Gossimer'
		'.12w.net',			// 67.15.219.253 by Marvin Dreyer (marvin.dreyer at pengs.com), ns *.gossimer.com
	),
	'*.urlproxy.com',
	'urlser.com',
	'urlsnip.com',
	'urlzip.de',
	'urlx.org',
	'useurl.us',		// by Edward Beauchamp (mail at ebvk.com)
	'utun.jp',
	'uxxy.com',
	'*.v27.net',
	'V3.com by FortuneCity.com' => array(	// http://www.v3.com/sub-domain-list.shtml
		'*.all.at',
		'*.back.to',
		'*.beam.at',
		'*.been.at',
		'*.bite.to',
		'*.board.to',
		'*.bounce.to',
		'*.bowl.to',
		'*.break.at',
		'*.browse.to',
		'*.change.to',
		'*.chip.ms',
		'*.connect.to',
		'*.crash.to',
		'*.cut.by',
		'*.direct.at',
		'*.dive.to',
		'*.drink.to',
		'*.drive.to',
		'*.drop.to',
		'*.easy.to',
		'*.everything.at',
		'*.fade.to',
		'*.fanclub.ms',
		'*.firstpage.de',
		'*.fly.to',
		'*.flying.to',
		'*.fortunecity.co.uk',
		'*.fortunecity.com',
		'*.forward.to',
		'*.fullspeed.to',
		'*.fun.ms',
		'*.gameday.de',
		'*.germany.ms',
		'*.get.to',
		'*.getit.at',
		'*.hard-ware.de',
		'*.hello.to',
		'*.hey.to',
		'*.hop.to',
		'*.how.to',
		'*.hp.ms',
		'*.jump.to',
		'*.kiss.to',
		'*.listen.to',
		'*.mediasite.de',
		'*.megapage.de',
		'*.messages.to',
		'*.mine.at',
		'*.more.at',
		'*.more.by',
		'*.move.to',
		'*.musicpage.de',
		'*.mypage.org',
		'*.mysite.de',
		'*.nav.to',
		'*.notrix.at',
		'*.notrix.ch',
		'*.notrix.de',
		'*.notrix.net',
		'*.on.to',
		'*.page.to',
		'*.pagina.de',
		'*.played.by',
		'*.playsite.de',
		'*.privat.ms',
		'*.quickly.to',
		'*.redirect.to',
		'*.rulestheweb.com',
		'*.run.to',
		'*.scroll.to',
		'*.seite.ms',
		'*.shortcut.to',
		'*.skip.to snap.to',
		'*.soft-ware.de',
		'*.start.at',
		'*.stick.by',
		'*.surf.to',
		'*.switch.to',
		'*.talk.to',
		'*.tip.nu',
		'*.top.ms',
		'*.transfer.to',
		'*.travel.to',
		'*.turn.to',
		'*.vacations.to',
		'*.videopage.de',
		'*.virtualpage.de',
		'*.w3.to',
		'*.walk.to',
		'*.warp9.to',
		'*.window.to',
		'*.yours.at',
		'*.zap.to',
		'*.zip.to',
	),
	'VDirect.com' => array(
		'*.emailme.net',
		'*.getto.net',
		'*.inetgames.com',
		'*.netbounce.com',
		'*.netbounce.net',
		'*.oneaddress.net',
		'*.snapto.net',
		'*.vdirect.com',
		'*.vdirect.net',
		'*.webrally.net',
	),
	'venturenetworking.com',	// by Katharine Barbieri (domains at spyforce.com)
	'vgo2.com',
	'Voila.fr' => array('r.voila.fr'),	// Fix it
	'w3t.org',
	'wapurl.co.uk',
	'Wb.st' => array(
		'*.team.st',
		'*.wb.st',
	),
	'wbkt.net',
	'WebAlias.com' => array(
		'*.andmuchmore.com',
		'*.browser.to',
		'*.escape.to',
		'*.fornovices.com',
		'*.fun.to',
		'*.got.to',
		'*.hottestpix.com',
		'*.imegastores.com',
		'*.latest-info.com',
		'*.learn.to',
		'*.moviefever.com',
		'*.mp3-archives.com',
		'*.myprivateidaho.com',
		'*.radpages.com',
		'*.remember.to',
		'*.resourcez.com',
		'*.return.to',
		'*.sail.to',
		'*.sports-reports.com',
		'*.stop.to',
		'*.thrill.to',
		'*.tophonors.com',
		'*.uncutuncensored.com',
		'*.up.to',
		'*.veryweird.com',
		'*.way.to',
		'*.web-freebies.com',
		'.webalias.com',
		'*.webdare.com',
		'*.xxx-posed.com',
	),
	'webmasterwise.com',
	'witherst at hotmail.com' => array(	// by Tim Withers
		'*.associates-program.com',
		'*.casinogopher.com',
		'*.ezpagez.com',
		'*.vgfaqs.com',
	),
	'wittylink.com',
	'wiz.sc',			// tiny.cc related
	'X50.us' => array(
		'*.i50.de',
		'*.x50.us',
	),
	'big5.xinhuanet.com',	///gate/big5/
	'xhref.com',
	'Xn6.net' => array(
		'*.9ax.net',
		'*.xn6.net',
	),
	'*.xshorturl.com',		// by Markus Lee (soul_s at list.ru) 
	'.y11.net',
	'YESNS.com' => array(	// by Jae-Hwan Kwon (kwonjhpd at kornet.net)
		'*.yesns.com',
		'*.srv4u.net',
		//blogne.com
	),
	'yatuc.com',
	'yep.it',
	'yumlum.com',
	'yurel.com',
	'Z.la' => array(
		'z.la',
		't.z.la',
	),
	'zaable.com',
	'zapurl.com',
	'zarr.co.uk',
	'zerourl.com',
	'ZeroWeb.org' => array(
		'*.80t.com',
		'*.firez.org',
		'*.fizz.nu',
		'*.ingame.org',
		'*.irio.net',
		'*.v33.org',
		'*.zeroweb.org',
	),
	'zhukcity.ru',
	'zippedurl.com',
	'zr5.us',
	'*.zs.pl',
	'*.zu5.net',
	'zuso.tw',
	'*.zwap.to',
);

// --------------------------------------------------

$blocklist['A-2'] = array(

	// A-2: Dynamic DNS, Dynamic IP services, DNS vulnerabilities, or another DNS cases
	//
	//'*.dyndns.*',	// Wildcard for dyndns
	//
	'*.ddo.jp',				// by Kiyoshi Furukawa (furu at furu.jp)
	'ddns.ru' => array('*.bpa.nu'),
	'Dhs.org' => array(
		'*.2y.net',
		'*.dhs.org',
	),
	'*.dnip.net',
	'*.dyndns.co.za',
	'*.dyndns.dk',
	'*.dyndns.nemox.net',
	'DyDNS.com' => array(
		'*.ath.cx',
		'*.dnsalias.org',
		'*.dyndns.org',
		'*.homeip.net',
		'*.homelinux.net',
		'*.mine.nu',
		'*.shacknet.nu',
	),
	'*.dtdns.net',			// by jscott at sceiron.com
	'*.dynu.com',
	'*.dynup.net',
	'*.fdns.net',
	'J-Speed.net' => array(
		'*.bne.jp',
		'*.ii2.cc',
		'*.jdyn.cc',
		'*.jspeed.jp',
	),
	'*.mydyn.de',
	'*.nerdcamp.net',
	'No-IP.com' => array(
			'*.bounceme.net',
			'*.hopto.org',
			'*.myftp.biz',
			'*.myftp.org',
			'*.myvnc.com',
			'*.no-ip.biz',
			'*.no-ip.info',
			'*.no-ip.org',
			'*.redirectme.net',
			'*.servebeer.com',
			'*.serveblog.net',
			'*.servecounterstrike.com',
			'*.serveftp.com',
			'*.servegame.com',
			'*.servehalflife.com',
			'*.servehttp.com',
			'*.servemp3.com',
			'*.servepics.com',
			'*.servequake.com',
			'*.sytes.net',
			'*.zapto.org',
	),
	'*.opendns.be',
	'Yi.org' => array(	// by dns at whyi.org
		'*.yi.org',		// 64.15.155.86(susicivus.crackerjack.net)

		// 72.55.129.46(redirect.yi.org)
		'*.whyi.org',
		'*.weedns.com',
	),
	'*.zenno.info',
	'.cm',	// 'Cameroon' ccTLD, sometimes used as typo of '.com',
			// and all non-recorded domains redirect to 'agoga.com' now
			// http://money.cnn.com/magazines/business2/business2_archive/2007/06/01/100050989/index.htm
			// http://agoga.com/aboutus.html
);

// --------------------------------------------------

// B: Sample setting of:
// Jacked (taken advantage of) and cleaning-less sites
//
// Please notify us about this list with reason:
// http://pukiwiki.sourceforge.jp/dev/?BugTrack2%2F208

// $blocklist['B-1'] = array(
//
// 	// B-1: Web spaces
// 	//
// 	//   Messages from forerunners:
// 	//     activefreehost.com
// 	//       "We regret to inform you that ActiveFreeHost
// 	//        free hosting service has is now closed (as of
// 	//        September 18). We have been online for over
// 	//        two and half years, but have recently decided
// 	//        to take time for software improvement to fight
// 	//        with server abuse, Spam advertisement and
// 	//        fraud."
// 	//
// 	'*.0000host.com',		// 68.178.200.154, ns *.3-hosting.net
// 	'*.007ihost.com',		// 195.242.99.199(s199.softwarelibre.nl)
// 	'*.00bp.com',			// 74.86.20.224(layeredpanel.com -> 195.242.99.195) by admin at 1kay.com
// 	'0Catch.com related' => array(
// 		'*.0catch.com',		// 209.63.57.4 by Sam Parkinson (sam at 0catch.com), also zerocatch.com
//
// 		// 209.63.57.10(www1.0catch.com) by dan at 0catch.com, ns *.0catch.com
// 		'*.100freemb.com',		// by Danny Ashworth
// 		'*.exactpages.com',
// 		'*.fcpages.com',
// 		'*.wtcsites.com',
//
// 		// 209.63.57.10(www1.0catch.com) by domains at netgears.com, ns *.0catch.com
// 		'*.741.com',
// 		'*.freecities.com',
// 		'*.freesite.org',
// 		'*.freewebpages.org',
// 		'*.freewebsitehosting.com',
// 		'*.jvl.com',
//
// 		// 209.63.57.10(www1.0catch.com) by luke at dcpages.com, ns *.0catch.com
// 		'*.freespaceusa.com',
// 		'*.usafreespace.com',
//
// 		// 209.63.57.10(www1.0catch.com) by rickybrown at usa.com, ns *.0catch.com
// 		'*.dex1.com',
// 		'*.questh.com',
//
// 		// 209.63.57.10(www1.0catch.com), ns *.0catch.com
// 		'*.00freehost.com',		// by David Mccall (superjeeves at yahoo.com)
// 		'*.012webpages.com',	// by support at 0catch.com
// 		'*.150m.com',
// 		'*.1sweethost.com',		// by whois at bluehost.com
// 		'*.250m.com',			// by jason at fahlman.net
// 		'*.9cy.com',			// by paulw0t at gmail.com
// 		'*.angelcities.com',	// by cliff at eccentrix.com
// 		'*.arcadepages.com',	// by admin at site-see.com
// 		'*.e-host.ws',			// by dns at jomax.net
// 		'*.envy.nu',			// by Dave Ellis (dave at larryblackandassoc.com)
// 		'*.fw.bz',				// by ben at kuehl.as
// 		'*.freewebportal.com',	// by mmouneeb at hotmail.com
// 		'*.g0g.net',			// by
