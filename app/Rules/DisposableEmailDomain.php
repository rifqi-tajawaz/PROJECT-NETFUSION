<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class DisposableEmailDomain implements ValidationRule
{
    /**
     * List of common disposable email domains.
     */
    protected $disposableDomains = [
        // Common disposable email services
        '10minutemail.com', '10minutemail.org', '10minutemail.net',
        '20minutemail.com', '20minutemail.org', '20minutemail.net',
        '30minutemail.com', '30minutemail.org', '30minutemail.net',
        '60minutemail.com', '60minutemail.org', '60minutemail.net',
        'anonymbox.com', 'antichef.com', 'antichef.net',
        'beefmilk.com', 'binkmail.com', 'bobmail.info',
        'boxtemp.com', 'bugmenot.com', 'bumpmail.com',
        'cobisi.com', 'coolandwicked.com', 'coolimpool.org',
        'deadaddress.com', 'deadspam.com', 'discardmail.com',
        'discardmail.org', 'dodgeit.com', 'dodgit.com',
        'dontreg.com', 'dontsendmespam.com', 'dumpmail.de',
        'dumpmail.info', 'dumpmail.org', 'dumpmail.ws',
        'dumpyemail.com', 'e-mail.org', 'email60.com',
        'emailias.com', 'emailinfive.com', 'emailmiser.com',
        'emailsensei.com', 'emailtemporario.com.br', 'emailtemporar.ro',
        'emailtemporanea.com', 'emailthe.net', 'emailwarden.com',
        'emailz.ir', 'emz.net', 'enterto.com',
        'ephemail.net', 'etranquil.com', 'etranquil.net',
        'etranquil.org', 'explodemail.com', 'fakeinbox.com',
        'fakeinbox.info', 'fakeinbox.org', 'fakemail.fr',
        'fakemailgenerator.com', 'fakemailz.com', 'fivemail.de',
        'flashmail.pro', 'flurred.com', 'flyspam.com',
        'forgetmail.com', 'free-email.info', 'freemails.cf',
        'freemails.ga', 'freemails.ml', 'freemails.tk',
        'friendlymail.co', 'front14.org', 'get2mail.com',
        'get1mail.com', 'getairmail.com', 'getairmail.net',
        'getonemail.com', 'getmails.eu', 'ghostmail.de',
        'gishpuppy.com', 'gmial.com', 'goemailgo.com',
        'guerillamail.com', 'guerillamail.net', 'guerillamail.org',
        'guerrillamail.biz', 'guerrillamail.info', 'guerrillamailblock.com',
        'hacdc.com', 'hmail.us', 'hush.ai',
        'hush.com', 'hushmail.com', 'hushmail.me',
        'ignoremail.com', 'imgof.com', 'imails.info',
        'inbox.si', 'inbox2.info', 'incognitomail.com',
        'incognitomail.net', 'incognitomail.org', 'ipoo.org',
        'iwi.net', 'jetable.com', 'jetable.fr',
        'jetable.net', 'jetable.org', 'junk.to',
        'junk1e.com', 'killmail.com', 'killmail.net',
        'kimsunghee.com', 'kmail.li', 'koszmail.pl',
        'letthemeatspam.com', 'lhsw.com', 'lifebyfood.com',
        'link2mail.net', 'listbrowsers.com', 'litedrop.com',
        'lol.ovpn.to', 'lookugly.com', 'lopl.co.cc',
        'lortemail.dk', 'losemymail.com', 'lovebitco.in',
        'lr78.com', 'maboard.com', 'mail-filter.com',
        'mail-temporaire.com', 'mail-temporaire.fr', 'mail-temporaire.org',
        'mail2rss.org', 'mailblocks.com', 'mailcatch.com',
        'mailde.de', 'maildrop.cc', 'maileater.com',
        'mailexpire.com', 'mailfa.tk', 'mailforspam.com',
        'mailfreeonline.com', 'mailguard.me', 'mailinator.com',
        'mailinator.net', 'mailinator.org', 'mailinator.us',
        'mailinator2.com', 'mailinc.org', 'mailismagic.com',
        'mailme24.com', 'mailmetrash.com', 'mailmoat.com',
        'mailnator.com', 'mailnesia.com', 'mailnull.com',
        'mailox.net', 'mailpick.biz', 'mailquack.com',
        'mailrock.biz', 'mailsac.com', 'mailscrap.com',
        'mailseal.de', 'mailshell.com', 'mailsiphon.com',
        'mailslapping.com', 'mailtemp.info', 'mailtemporaire.com',
        'mailtome.de', 'mailtrash.net', 'mailtv.net',
        'mailtv.tv', 'mailzi.info', 'makemethe.com',
        'mbx.cc', 'mega.zik.dj', 'meltmail.com',
        'mfsa.ru', 'mintemail.com', 'moburl.com',
        'monumentmail.com', 'msa.minsmail.com', 'mt2014.com',
        'mx0.wwwnew.eu', 'my10minutemail.com', 'mycleaninbox.net',
        'mydecoy.com', 'myemailboxy.com', 'mymail-in.net',
        'mymailoasis.com', 'mypacks.net', 'myspaceinc.com',
        'myspaceinc.net', 'myspaceinc.org', 'myspamless.com',
        'mytemp.email', 'mytempemail.com', 'mytempmail.com',
        'mytrashmail.com', 'naver.com', 'nepwk.com',
        'nervmich.net', 'nervtmich.net', 'netmails.com',
        'netmails.net', 'netzidiot.de', 'nimplant.com',
        'no-spam.ws', 'no-spam.org', 'no-spam.info',
        'nobugmail.com', 'nobulk.com', 'noclickemail.com',
        'nogmailspam.info', 'nomail.pw', 'nomail2me.com',
        'nomorespamemails.com', 'nospam4.us', 'nospamfor.us',
        'nospamthanks.info', 'notmailinator.com', 'nowmymail.com',
        'nurfuerspam.de', 'nus.edu.sg', 'nwldx.com',
        'objectmail.com', 'obobbo.com', 'odaymail.com',
        'odaymail.net', 'odaymail.org', 'oneoffemail.com',
        'onewaymail.com', 'online.ms', 'opayq.com',
        'ordinaryamerican.net', 'otherinbox.com', 'ovpn.to',
        'owlpic.com', 'pancakemail.com', 'pimpedupmyspace.com',
        'pjjkp.com', 'politikerclub.de', 'poofy.org',
        'pookmail.com', 'privymail.de', 'prtnx.com',
        'punkass.com', 'put2mail.com', 'putthisinyourspamdatabase.com',
        'quickmail.nl', 'rcpt.at', 'realtyalerts.ca',
        'recode.me', 'recursor.net', 'regbypass.com',
        'regbypass.comsafe-mail.net', 'rejectmail.com', 'rklips.com',
        'rklips.org', 'rroxx.com', 'safersignup.de',
        'safetymail.info', 'safetypost.de', 'saharanight.net',
        'selfdestructingmail.com', 'selfdestructingmail.org',
        'sendfree.org', 'sendingspamfree.com', 'senseless-entertainment.com',
        'serverheaven.de', 'sharklasers.com', 'shitmail.me',
        'shitware.nl', 'shmer.com', 'sibmail.com',
        'skeefmail.com', 'skkkmail.com', 'slushmail.com',
        'smellyemail.com', 'smtp.nv' , 'snakemail.com',
        'sneakemail.com', 'sneakmail.de', 'snkmail.com',
        'socrafty.com', 'sofort-mail.de', 'sofortmail.de',
        'sohu.com', 'sogetthis.com', 'soodonims.com',
        'spam.la', 'spam.su', 'spam4.me',
        'spamavert.com', 'spambob.com', 'spambob.net',
        'spambob.org', 'spambog.com', 'spambog.de',
        'spambog.ru', 'spambox.info', 'spambox.irishspringrealty.com',
        'spambox.org', 'spambox.us', 'spamcannon.com',
        'spamcannon.net', 'spamcannon.org', 'spamcon.org',
        'spamcorptastic.com', 'spamcowboy.com', 'spamcowboy.net',
        'spamcowboy.org', 'spamday.com', 'spamdecoy.net',
        'spamex.com', 'spamfighter.com', 'spamfighter.org',
        'spamfree24.com', 'spamfree24.de', 'spamfree24.eu',
        'spamfree24.info', 'spamfree24.net', 'spamfree24.org',
        'spamgoes.in', 'spamgourmet.com', 'spamgourmet.net',
        'spamgourmet.org', 'spamherelots.com', 'spamhole.com',
        'spamhole.info', 'spamhole.org', 'spamify.com',
        'spaminator.de', 'spamkill.info', 'spaml.com',
        'spaml.de', 'spammotel.com', 'spamobox.com',
        'spamoff.de', 'spamsafe.de', 'spamslicer.com',
        'spamspot.com', 'spamthis.co.uk', 'spamthisplease.com',
        'spamtroll.net', 'spamtrail.com', 'spaml.com',
        'speed.1s.fr', 'spoofmail.de', 'sr.ro',
        'ssoia.com', 'startkeys.com', 'stexsy.com',
        'stoicmed.com', 'stop-my-spam.com', 'stop-my-spam.de',
        'stop-my-spam.eu', 'stop-my-spam.info', 'stop-my-spam.net',
        'stop-my-spam.org', 'streetwisemail.com', 'stuffmail.de',
        'super-mailer.com', 'superplatyna.com', 'superstachel.de',
        'supergreatmail.com', 'supermailer.jp', 'supergreatmail.com',
        'supermailer.jp', 'svk.jp', 'sweetxxx.de',
        'tagyourself.com', 'talkinator.com', 'tapchicuoi.com',
        'temp-mail.org', 'temp-mail.ru', 'temp-mail.com',
        'temp-mail.de', 'temp-mail.io', 'tempalias.com',
        'tempemail.co.za', 'tempemail.net', 'tempe-mail.com',
        'tempemail.biz', 'tempemail.co', 'tempemail.com',
        'tempemail.net', 'tempemail.org', 'tempmail.co',
        'tempmail.de', 'tempmail.eu', 'tempmail.it',
        'tempmail.org', 'tempmail.pp.ua', 'tempmaildemo.com',
        'temporarily.de', 'temporarioemail.com.br', 'temporaryemail.org',
        'temporaryemailaddress.com', 'temporarymail.co', 'temporarymail.net',
        'temporarily.de', 'temporarily.org', 'temporarily.us',
        'tempsky.com', 'tempthe.net', 'tempymail.com',
        'thankyou2010.com', 'thatim.info', 'thc.li',
        'the-fastest.net', 'the-little-api.com', 'thebigday.org',
        'thecloudindex.com', 'thecorporation.info', 'thedirtons.info',
        'thelimiteds.com', 'themail.co', 'thenews.com.tr',
        'theone.com', 'thequeban.com', 'therealpin.com',
        'theteamny.com', 'theteenzone.info', 'thisisnotmyrealemail.com',
        'thismail.net', 'thisurl.website', 'throwawayemailaddress.com',
        'tilien.com', 'tittbit.in', 'tittbit.org',
        'tmail.com', 'tmailinator.com', 'tmails.net',
        'toiea.com', 'toomail.biz', 'top1mail.ru',
        'top-of-the-world.net', 'topranklist.de', 'trash-amil.com',
        'trash-mail.at', 'trash-mail.com', 'trash-mail.de',
        'trash-mail.ga', 'trash-mail.ml', 'trash-mail.tk',
        'trash2009.com', 'trashemail.de', 'trashmail.com',
        'trashmail.net', 'trashmail.org', 'trashmail.ws',
        'trashmailer.com', 'trashymail.com', 'trashymail.net',
        'trashymail.org', 'trillianpro.com', 'tryalert.com',
        'twinzmail.com', 'ty.ceed.se', 'uacro.com',
        'ubismail.net', 'udoemail.com', 'ufacturing.com',
        'uguuchagi.com', 'ukrgold.com', 'umail.net',
        'unclepepe.com', 'unmail.ru', 'upliftnow.com',
        'upliftnow.org', 'uplipht.com', 'uroid.com',
        'us.af', 'uu2.com', 'uwork4.us',
        'venompen.com', 'veryrealemail.com', 'verywoman.eu',
        'victoriantreasury.com', 'viditag.com', 'viewcastmedia.com',
        'viewcastmedia.net', 'viewcastmedia.org', 'vpn.st',
        'vsimcard.com', 'vubby.com', 'wasteland.rfc822.org',
        'web-email.org', 'webemail.me', 'webm4il.info',
        'webmail24.top', 'weg-werf-email.de', 'wegwerfadresse.com',
        'wegwerfemail.com', 'wegwerfmail.de', 'wegwerfmail.net',
        'wegwerfmail.org', 'whatiaas.com', 'whispy.com',
        'whyspam.me', 'williamgordon.org', 'wilsonl.in',
        'winemaven.info', 'wronghead.com', 'wuzup.net',
        'wuzupmail.net', 'www.e4ward.com', 'www.gishpuppy.com',
        'www.mailinator.com', 'wwwnew.eu', 'xagloo.com',
        'xemaps.com', 'xents.com', 'xmail.com',
        'xoxy.com', 'xoxox.cc', 'yep.it',
        'yogamaven.com', 'yopmail.com', 'yopmail.fr',
        'yopmail.net', 'youneedmore.info', 'yourdomain.com',
        'youremail.com', 'yourlifesucks.com', 'ypmail.webarnak.fr.eu.org',
        'yspend.com', 'yugasandrika.com', 'z1p.biz',
        'z10z.com', 'zehnminutenmail.de', 'zipzap.thack.us',
        'zippymail.info', 'zoemail.com', 'zoemail.net',
        'zoemail.org', 'zoetropes.org', 'zombie-horse.com',
        'zomg.info', 'zxcv.com', 'zxcvbnm.com',
        'zzz.com',
    ];

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $email = (string) $value;
        $domain = strtolower(substr(strrchr($email, '@'), 1));

        if ($this->isDisposableDomain($domain)) {
            $fail('Disposable email addresses are not allowed. Please use a permanent email address.');
            return;
        }

        // Additional checks
        if ($this->isSuspiciousDomain($domain)) {
            $fail('The email domain appears to be suspicious. Please use a different email address.');
            return;
        }
    }

    /**
     * Check if domain is in disposable list.
     */
    protected function isDisposableDomain(string $domain): bool
    {
        // Exact match
        if (in_array($domain, $this->disposableDomains)) {
            return true;
        }

        // Subdomain match (e.g., mailinator.com and test.mailinator.com)
        foreach ($this->disposableDomains as $disposable) {
            if (str_ends_with($domain, '.' . $disposable)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check for suspicious domain patterns.
     */
    protected function isSuspiciousDomain(string $domain): bool
    {
        $suspiciousPatterns = [
            // Temporary/TLD patterns
            '/\.(tk|ml|ga|cf|gq)$/i',

            // Numeric heavy domains
            '/^[0-9]{3,}/',

            // Random string patterns (high entropy)
            '/^[a-z]{20,}$/i',

            // Suspicious keywords
            '/(temp|throwaway|disposable|fake|spam|junk|temp|tempmail|10min|20min|30min|hour|day)/i',
        ];

        foreach ($suspiciousPatterns as $pattern) {
            if (preg_match($pattern, $domain)) {
                return true;
            }
        }

        // Check MX records (temporary emails often don't have valid MX records)
        if (!checkdnsrr($domain, 'MX')) {
            return true;
        }

        return false;
    }
}
