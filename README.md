HwSEO
========

HwSEO contains helpers used for SEO purposes in eZPublish (tested and developed on community project 2011.7).

hwseoredirect
-------------

Given you have a multilingual site (www.example.com) containing 3 siteaccesses (de, en, admin) and you are using translated URL aliases. You have configured the siteaccesses to use the following host-based matching:

    http://www.example.com     en
    http://de.example.com      de
    http://admin.example.com   admin

A translated page containing job posts will by default available on the following URLs:

    http://www.example.com/career
    http://de.example.com/karriere

However, you (and search engines) will be able to access the same page on all URL translations, which is not clean and bad for SEO purposes (duplicate content). E.g.:

    http://www.example.com/karriere
    http://de.example.com/career

A common example is the default language switcher, which will redirect to the URL containing the alias from the current translation. If you are on the english page and you want to switch to the german siteaccess, the languageswitcher will generate the URL http://www.example.com/switchlanguage/to/en/career which will redirect you to http://de.example.com/career.

The <code>hwseoredirect</code> operator takes checks if the current node is using the correct translation of the URL alias and redirects to the translated one in case of another translation (will send a 301 response to redirect to http://de.example.com/karriere in the example above).

### Usage

Use the operator in your pagelayout.tpl after you got the pagedata object.

    {$pagedata|hwseoredirect()}

### Configuration

You can specify an array of blacklisted nodes in <code>hwseo.ini</code>, which will be ignored. This is useful for start/frontpages where you want to avoid a redirect from http://www.example.com/ to http://www.example.com/index.

Installation
------------

Drop the code to <code>extension/hwseo</code>. Then, activate the extension for your site/siteaccess. E.g. in <code>settings/override/site.ini.append.php</code>:

    ActiveExtensions[]=hwseo