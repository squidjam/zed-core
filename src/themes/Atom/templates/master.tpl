{nocache}{php}header("Content-type: application/atom+xml");{/php}{/nocache}
<?xml version="1.0" encoding="{charset}"?>
<feed xmlns="http://www.w3.org/2005/Atom">
    <link rel="alternate" type="text/html" href="{getbaseurl}" />
    <link rel="self" type="application/atom+xml" href="{getcurrenturl}" />
    <title>{sitename}</title>
    <subtitle>{slogan}</subtitle>
    <id>{id}</id>
    <updated>{updated}</updated>
    <author>
        <name>{configgetvar name=adminmail}</name>
    </author>
    <generator>{configgetvar name=Version_ID}</generator>
    <rights>Copyright {configgetvar name=sitename}</rights>
    {$maincontent}
</feed>