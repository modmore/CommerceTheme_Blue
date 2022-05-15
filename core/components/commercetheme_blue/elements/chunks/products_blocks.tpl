
<div class="product-option">
    <!-- Active: "ring-2 ring-indigo-500" -->
    <input type="radio" name="product" value="[[+id]]" id="product-option-[[+id]]" class="product-option__selector" [[+idx:eq=`0`:then=`checked="checked"`:else=``]]">
    <label class="product-option__label" for="product-option-[[+id]]">
        <span class="product-option__label-content">
            <span class="product-option__label-title">[[+name:htmlent]]</span>
            [[+description:notempty=`<span class="product-option__label-description">[[+description:htmlent:nl2br]]</span>`:default=``]]
        </span>

        <span class="product-option__label-price">[[+price_rendered]]</span>
    </label>
</div>
