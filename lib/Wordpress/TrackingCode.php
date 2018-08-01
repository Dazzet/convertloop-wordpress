<?php namespace WpConvertloop\Wordpress;

class TrackingCode
{
    /** @var The App ID code */
    private $appId;

    static function instance($appId)
    {
        static $obj;
        if (!isset($obj)) {
            $obj = new self($appId);
        }
        return $obj;
    }

    private function __construct($appId)
    {
        $this->appId = $appId;
    }

    public function start()
    {
        add_action('wp_head', array($this, 'enqueueJavaScript'));
        return $this;
    }

    public function enqueueJavaScript()
    {
?>
<!-- ConvertLoop -->
<script>
!function(t,e,n,s) { t.DPEventsFunction=s,t[s]=t[s] || function() { (t[s].q=t[s].q||[]).push(arguments) }; var c=e.createElement("script"),o=e.getElementsByTagName("script")[0]; c.async=1,c.src=n,o.parentNode.insertBefore(c,o); }(window, document, "https://www.convertloop.co/v1/loop.min.js", "_dp");

_dp("configure", { appId: "<?php echo $this->appId ?>", autoTrack: true });
_dp("pageView");
</script>
<!-- End ConvertLoop -->
<?php
        return $this;
    }
}
