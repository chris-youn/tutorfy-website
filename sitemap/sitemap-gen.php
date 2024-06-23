<?php
class SitemapGenerator
{
    private $config;
    private $scanned;
    private $site_url_base;

    public function __construct($conf)
    {
        $this->config = $conf;
        $this->scanned = [];
        $this->site_url_base = rtrim($this->config['SITE_URL'], '/');
    }

    public function GenerateSitemap()
    {
        foreach ($this->config['FOLDERS_TO_INCLUDE'] as $folder => $files) {
            foreach ($files as $file) {
                $fileUrl = $this->site_url_base . '/' . $folder . '/' . $file;
                $fileUrl = str_replace('/sitemap/', '/', $fileUrl); // Remove /sitemap/ from the URL
                if (!in_array($fileUrl, $this->scanned)) {
                    $this->scanned[] = $fileUrl;
                }
            }
        }
        $this->generateFile($this->scanned);
    }

    private function generateFile($pages)
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>
        <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
        <!-- ' . count($pages) . ' total pages-->';

        foreach ($pages as $page) {
            $xml .= '<url>
                <loc>' . htmlspecialchars($page) . '</loc>
                <lastmod>' . $this->config['LAST_UPDATED'] . '</lastmod>
                <changefreq>' . $this->config['CHANGE_FREQUENCY'] . '</changefreq>
                <priority>' . $this->config['PRIORITY'] . '</priority>
            </url>';
        }

        $xml .= "</urlset>";
        $xml = str_replace('&', '&amp;', $xml);

        $doc = new DOMDocument();
        $doc->preserveWhiteSpace = false;
        $doc->formatOutput = true;
        $doc->loadXML($xml);

        $formattedXml = $doc->saveXML();

        echo '<html><head><title>XML Display</title></head><body>';
        echo '<h1>XML Data</h1>';
        echo '<pre>';
        echo htmlentities($formattedXml);
        echo '</pre>';
        echo '</body></html>';
    }
}

$config = include("sitemap-config.php");

$generator = new SitemapGenerator($config);
$generator->GenerateSitemap();
?>
