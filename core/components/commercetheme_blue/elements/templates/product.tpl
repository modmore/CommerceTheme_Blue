[[$ctblue.header?
    &extra=``
    &showBreadcrumbs=`1`
]]

[[!commerce.get_product? &product=`[[*products]]` &toPlaceholders=`product`]]

<main role="main" class="container">

    <div class="flex-row product-row">
        <div class="product-image product-image__primary">
            <img src="/[[!+product.image]]" alt="[[*longtitle:default=`[[*pagetitle]]`:htmlent]]">
        </div>

        <div class="product-content">
            <h1 class="product-title">[[*longtitle:default=`[[*pagetitle]]`:htmlent]]</h1>

            [[- <p class="product-pricing">[ [!+product.price_rendered] ]</p> ]]

            <p class="product-description">[[*introtext:default=`[[*description]]`:default=`[[!+product.description]]`:nl2br]]</p>


            <form method="post" action="[[~[[++commerce.cart_resource]]]]" class="product-add-to-cart add-to-cart add-to-cart__list">
                <input type="hidden" name="add_to_cart" value="1">
                <input type="hidden" name="link" value="[[*id]]">

                <fieldset class="product-options">
                    <legend class="product-options-legend">Options</legend>
                    <div class="product-options-list">
                        [[!commerce.get_products?
                            &products=`[[*products]]`
                            &tpl=`ctblue.products_blocks`
                        ]]
                    </div>
                </fieldset>

                <p style="color: #64748B; font-size: 0.9rem; font-weight: 600; text-align: center;">Looking to attend remotely? <a href="#">Video Tickets are available here</a></p>


                [[- The below is useful for adding additional options with ItemData/ItemOptions ]]

                <fieldset class="product-options">
                    <legend class="product-options-legend">Ticket Details</legend>

                    <div class="c-field">
                        <label for="option-firstname">First name</label>
                        <input type="text" name="firstname" id="option-firstname">
                    </div>
                    <div class="c-field">
                        <label for="option-lastname">Last name</label>
                        <input type="text" name="lastname" id="option-lastname">
                    </div>
                    <div class="c-field">
                        <label for="option-pronouns">Pronouns</label>
                        <select name="pronouns" id="option-pronouns">
                            <option>he / him</option>
                            <option>she / her</option>
                            <option>they / them</option>
                        </select>
                    </div>
                    <div class="c-field">
                        <label for="option-dietary">Dietary requests</label>
                        <textarea name="dietary" id="option-dietary" rows="5"></textarea>
                    </div>
                </fieldset>

                <div class="product-buttons">
                    <button type="submit" class="c-button add-to-cart__button">[[!%commerce.add_to_cart]]</button>
                </div>

            </form>

        </div>
    </div>

    <p id="add-to-cart-error" aria-live="polite"></p>
    <p id="add-to-cart-success" aria-live="polite"></p>

    [[*content]]

</main>

[[$ctblue.footer?
    &extra=``
]]
