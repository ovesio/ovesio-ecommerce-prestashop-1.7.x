<div class="panel">
    <h3><i class="icon icon-cogs"></i> {l s='Configuration' mod='ovesio_ecommerce'}</h3>
    <p>
        <strong>{l s='Connect your store to Ovesio to unlock powerful capabilities:' mod='ovesio_ecommerce'}</strong><br />
        {l s='Stock Management, Forecasting, Pricing Strategy & more.' mod='ovesio_ecommerce'}
    </p>
    <div class="alert alert-info">
        <p>{l s='Please configure the "Order Export Period" below and click Save.' mod='ovesio_ecommerce'}</p>
        <p>{l s='Then copy the following URLs and paste them into your Ovesio dashboard.' mod='ovesio_ecommerce'}</p>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <br />
    <div class="form-wrapper">
        <div class="form-group">
            <label class="control-label col-lg-3">{l s='Product Feed URL' mod='ovesio_ecommerce'}</label>
            <div class="col-lg-9">
                <div class="input-group">
                    <input type="text" value="{$product_feed_url}" readonly class="form-control">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="button" onclick="copyToClipboard('{$product_feed_url}')"><i class="icon-copy"></i> {l s='Copy' mod='ovesio_ecommerce'}</button>
                    </span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-3">{l s='Order Feed URL' mod='ovesio_ecommerce'}</label>
            <div class="col-lg-9">
                <div class="input-group">
                    <input type="text" value="{$order_feed_url}" readonly class="form-control">
                    <span class="input-group-btn">
                         <button class="btn btn-default" type="button" onclick="copyToClipboard('{$order_feed_url}')"><i class="icon-copy"></i> {l s='Copy' mod='ovesio_ecommerce'}</button>
                    </span>
                </div>
            </div>
        </div>
            </div>
        </div>
    </div>
    <hr />
    <div class="alert alert-warning">
         <strong>{l s='Security Hash' mod='ovesio_ecommerce'}:</strong> {$hash}<br />
         {l s='If you uninstall and reinstall this module, this hash will change and you will need to update your URLs in Ovesio.' mod='ovesio_ecommerce'}
    </div>
</div>

<script type="text/javascript">
    function copyToClipboard(text) {
        var dummy = document.createElement("textarea");
        document.body.appendChild(dummy);
        dummy.value = text;
        dummy.select();
        document.execCommand("copy");
        document.body.removeChild(dummy);
        showSuccessMessage("{l s='Copied to clipboard' mod='ovesio_ecommerce'}");
    }
</script>
