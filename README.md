HwSEO
========

HwSEO contains helpers used for SEO purposes in eZPublish (tested and developed on community project 2011.7).

<code>hwseoredirect</code>
--------------------------

Given you have multilingual site (www.example.com) containing 3 siteaccesses (de, en, admin) and you are using translated URL aliases. You have configured the siteaccesses to use the following host-based matching:

    www.example.com     en
    de.example.com      de
    admin.example.com   admin

A translated page containing job posts is by default available on the following URLs:

    www.example.com/career
    de.example.com/karriere

However, you (and search engines) will be able to access the same page on all URL translations, which is not clean and bad for SEO purposes (duplicate content). E.g.:

    www.example.com/karriere
    de.example.com/career

A common example is the default language switcher, which will redirect to the URL containing the alias from the current translation. If you are on the english page and you want to switch to the german siteaccess, the languageswitcher will generate the URL <code>http://www.example.com/switchlanguage/to/en/career</code> which will redirect you to <code>http://de.example.com/career</code>.

The <code>hwseoredirect</code> operator takes checks if the current node is using the correct translation of the URL alias and redirects to the translated one in case of another translation.

### Usage

Use the operator in your pagelayout.tpl after you got the pagedata object.

    {$pagedata|hwseoredirect()}

### Configuration

You can specify an array of blacklisted nodes in <code>hwseo.ini</code>, which will be ignored. This is useful for start/frontpages where you want to avoid a redirect from <code>http://www.example.com/</code> to <code>http://www.example.com/index</code>.

Installation
------------

Drop <code>extension/hwseo</code> to your extension directory. Then, activate the extension for your site/siteaccess. E.g. in <code>settings/override/site.ini.append.php</code>:

    ActiveExtensions[]=hwseo