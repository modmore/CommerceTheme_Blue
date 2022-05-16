(function() {
    let cart, checkout;
    onReady(function () {
        cart = document.querySelector('.c-cart-container');
        if (cart) {
            initializeCartEnhancements(cart);
        }
        checkout = document.querySelector('.checkout-container');
        if (checkout) {
            initializeCheckoutEnhancements(checkout);
        }
        updateMiniCart();
        window.updateMiniCart = updateMiniCart;

        window.onpopstate = function() {
            if (checkout && window.location.href.indexOf(CommerceConfig.checkout_url) !== -1) {
                checkout.classList.add('commerce-loader');
                _request('GET', window.location.href, null, _handleCheckoutResponse);
            }
            else {
                window.location = window.location.href;
            }
        };

        // Add loader to the checkout form in the minicart
        let minicartCheckout = document.querySelector('.minicart__footer');
        if (minicartCheckout && minicartCheckout.nodeName === 'FORM') {
            minicartCheckout.addEventListener('submit', function() {
                // We could, theoretically, intercept this submit and run it with AJAX, but we're not sure
                // at this point if all checkout-related assets are loaded, so we're going with a standard
                // synchronous submit.
                minicartCheckout.classList.add('commerce-loader');
            });
        }

        let addToCart = document.querySelector('form.add-to-cart');
        if (addToCart) {
            addToCart.addEventListener('submit', function(e) {
                e.preventDefault();
                addToCart.classList.add('commerce-loader');
                _request(
                    'POST',
                    addToCart.getAttribute('action'),
                    new FormData(addToCart),
                    function(result) {
                        addToCart.classList.remove('commerce-loader');
                        _updateMiniCartResponse(result);

                        if (result.errors && result.errors.length > 0) {
                            let errorBox = document.getElementById('add-to-cart-error');
                            if (errorBox) {
                                errorBox.innerText = '';
                                result.errors.forEach(function(err) {
                                    errorBox.innerText += ' ' + err.message;
                                });

                                errorBox.classList.add('error-visible');

                                setTimeout(function() {
                                    let errorBox = document.getElementById('add-to-cart-error');
                                    if (errorBox) {
                                        errorBox.classList.remove('error-visible');
                                    }
                                }, 7500)
                            }
                        }
                        else if (result.message) {
                            let successBox = document.getElementById('add-to-cart-success');
                            if (successBox) {
                                successBox.innerHTML = '';

                                let msg = document.createElement('span');
                                msg.innerText = result.message;
                                successBox.appendChild(msg);

                                let link = document.createElement('a');
                                link.setAttribute('href', window.CommerceConfig.cart_url);
                                link.classList.add('c-button');
                                link.innerText = 'View Cart'; // @todo
                                successBox.appendChild(link);

                                successBox.classList.add('success-visible');

                                setTimeout(function() {
                                    let successBox = document.getElementById('add-to-cart-success');
                                    if (successBox) {
                                        successBox.classList.remove('success-visible');
                                    }
                                }, 7500)
                            }

                            addToCart.reset();
                        }
                    }
                );
            });
        }
    });

    function onReady (callback) {
        if (document.readyState !== 'loading') {
            callback();
        }
        else if (document.addEventListener) {
            document.addEventListener('DOMContentLoaded', callback);
        }
        else {
            document.attachEvent('onreadystatechange', function() {
                if (document.readyState !== 'loading') {
                    callback();
                }
            });
        }
    }

    function initializeCartEnhancements(cart) {
        let quantities = cart.querySelectorAll('.cart-item__quantityfld');

        if (quantities.length > 0) {
            quantities.forEach(function (quantityFld) {
                let btn = quantityFld.querySelector('button');
                if (btn) {
                    btn.style.display = 'none';
                }

                let select = quantityFld.querySelector('select');
                if (select) {
                    select.addEventListener('change', function() {
                        select.form.classList.add('commerce-loader');
                        _request('POST', CommerceConfig.cart_url, new FormData(select.form), _refreshCart);
                    })
                }

            });
        }

        let couponForm = cart.querySelector('.c-cart-coupon-form');
        if (couponForm) {
            couponForm.addEventListener('submit', function(e) {
                e.preventDefault();
                couponForm.classList.add('commerce-loader');
                _request('POST', CommerceConfig.cart_url, new FormData(couponForm), _refreshCart);
            });
        }

        let checkoutForms = cart.querySelectorAll('.c-cart-submit');
        checkoutForms.forEach(function(checkoutForm) {
            checkoutForm.addEventListener('submit', function(e) {
                e.preventDefault();
                cart.classList.add('commerce-loader');
                _request('POST', CommerceConfig.cart_url, new FormData(checkoutForm), function(response) {
                    if (response.success && response.redirect) {
                        window.location = response.redirect;
                    }
                    else {
                        _refreshCart(response);
                        cart.classList.remove('commerce-loader');
                    }
                });
            });

        });
    }

    function _refreshCart(response) {
        let responseDom = document.createElement('div'),
            currentFocus = document.activeElement && document.activeElement.id ? document.activeElement.id : false;
        responseDom.innerHTML = response.output;

        cart.innerHTML = responseDom.innerHTML;
        initializeCartEnhancements(cart);

        if (currentFocus) {
            let newFocus = document.getElementById(currentFocus);
            if (newFocus && newFocus.focus) {
                newFocus.focus();
            }
        }

        _updateMiniCartResponse(response);
    }

    function initializeCheckoutEnhancements(checkout) {
        let forms = checkout.querySelectorAll('form');
        forms.forEach(function(form) {
            let target = form.getAttribute('action');
            if (target.indexOf(CommerceConfig.checkout_url) === -1) {
                return;
            }

            // Do not run on the payment form; assume the gateways handle that.
            if (form.classList.contains('c-choose-payment-form')) {
                return;
            }

            form.addEventListener('submit', function(e) {
                e.preventDefault();
                checkout.classList.add('commerce-loader');

                _request('POST', form.getAttribute('action'), new FormData(form), _handleCheckoutResponse);
            });

            if (form.classList.contains('step-autosubmit')) {
                form.addEventListener('change', function() {
                    if (form.checkValidity()) {
                        checkout.classList.add('commerce-loader');
                        _request('POST', form.getAttribute('action'), new FormData(form), _handleCheckoutResponse);
                    }
                });

                let buttons = form.querySelectorAll('.step-autosubmit-hide');
                buttons.forEach(function(btn) {
                    btn.style.display = 'none';
                });
            }
        });

        let steps = checkout.querySelectorAll('a');
        steps.forEach(function(link) {
            let target = link.getAttribute('href');
            if (target.indexOf(CommerceConfig.checkout_url) === -1) {
                return;
            }

            link.addEventListener('click', function(e) {
                e.preventDefault();
                checkout.classList.add('commerce-loader');
                window.history.pushState(null, '', target);
                _request('GET', target, null, _handleCheckoutResponse);
            })
        });
    }

    function _handleCheckoutResponse(result) {
        if (result.errors && result.errors.length > 0) {
            let errorBox = document.getElementById('checkout-error');
            if (errorBox) {
                errorBox.innerText = '';
                result.errors.forEach(function(err) {
                    errorBox.innerText += ' ' + err.message;
                });

                errorBox.classList.add('error-visible');

                setTimeout(function() {
                    let errorBox = document.getElementById('checkout-error');
                    if (errorBox) {
                        errorBox.classList.remove('error-visible');
                    }
                }, 7500)
            }
        }

        if (result.redirect) {
            // Account for GET or POST-style redirects
            if (result.redirect_method === 'GET') {
                // Make sure we only redirect to the same origin
                if (result.redirect.substring(0, location.origin.length) !== location.origin) {
                    window.location = result.redirect;
                }
                else {
                    window.history.pushState(null, '', result.redirect);
                    _request('GET', result.redirect, null, _handleCheckoutResponse);
                }
            }
            // For POST redirects (i.e. some payment gateways), create a dynamic form with the redirect_data and submit it
            else if (result.redirect_method === 'POST') {
                let form = document.createElement('form');
                form.setAttribute('action', result.redirect);
                form.setAttribute('method', 'POST');


                for (let key in result.redirect_data) {
                    if (!result.redirect_data.hasOwnProperty(key)) {
                        continue;
                    }
                    let input = document.createElement('input');
                    input.setAttribute('type', 'hidden');
                    input.setAttribute('name', key);
                    input.setAttribute('value', result.redirect_data[key]);
                    form.appendChild(input);
                }

                checkout.appendChild(form);
                form.submit();
            }
        }
        else {
            let stepWrapperDom = checkout.querySelector('.c-checkout'),
                responseDom = document.createRange();
            if (result.output) {
                responseDom = responseDom.createContextualFragment(result.output);
            }
            else {
                responseDom = responseDom.createContextualFragment(result);
            }
            stepWrapperDom.innerHTML = '';


            while (responseDom.firstChild) {
                stepWrapperDom.appendChild(responseDom.firstChild);
            }

            // responseDom.childNodes.forEach(function(childNode) {
            //     stepWrapperDom.appendChild(childNode);
            // });
            checkout.classList.remove('commerce-loader');
            initializeCheckoutEnhancements(checkout);

            let current = document.querySelector('[data-current-step]');
            if (current && current.scrollIntoView) {
                current.scrollIntoView({block: "start", inline: "nearest", behavior: "smooth"});
            }
        }
    }


    function updateMiniCart() {
        _request('GET', CommerceConfig.cart_url, {}, _updateMiniCartResponse);
    }
    function _updateMiniCartResponse(response) {
        let itemWrapper = document.querySelector('.minicart__items');
        if (itemWrapper) {
            // Empty wrapper
            while (itemWrapper.firstChild) {
                itemWrapper.removeChild(itemWrapper.firstChild);
            }

            if (response.message) {
                let msg = document.createElement('li');
                msg.classList.add('minicart__message');
                msg.innerHTML = response.message;
                itemWrapper.appendChild(msg);

                // setTimeout(function() {
                //     itemWrapper.removeChild(msg);
                // }, 5000);
            }
            if (response.errors) {
                response.errors.forEach(function(err) {
                    let msg = document.createElement('li');
                    msg.classList.add('minicart__message');
                    msg.classList.add('minicart__error');
                    msg.innerHTML = err.message;
                    itemWrapper.appendChild(msg);
                    // setTimeout(function() {
                    //     itemWrapper.removeChild(msg);
                    // }, 7500);
                })
            }

            response.order.items.forEach(function (item) {
                let li = document.createElement('li');
                li.classList.add('minicart__item');

                if (item.image && item.image !== '') {
                    let img = document.createElement('img');
                    img.classList.add('minicart__image');
                    img.addEventListener('error', function () {
                        this.style.display = 'none';
                    });
                    img.setAttribute('src', item.image);

                    li.appendChild(img);
                }

                let content = document.createElement('div');
                content.classList.add('minicart__content');

                let name = document.createElement('div');
                name.classList.add('minicart__name');
                name.innerText = item.name;

                // @todo Quantity changer
                // let quantity = document.createElement('span');
                // quantity.classList.add('minicart__quantity');
                // quantity.innerText = item.quantity;
                // name.appendChild(quantity);

                content.appendChild(name);

                let priceWrapper = document.createElement('span');
                priceWrapper.classList.add('minicart__price-wrapper');

                if (item.discount !== 0) {
                    let discount = document.createElement('del');
                    discount.classList.add('cart-item__subtotal_old');
                    discount.innerHTML = item.subtotal_formatted;
                    priceWrapper.appendChild(discount);
                }

                let price = document.createElement('span');
                price.classList.add('minicart__price');
                price.innerHTML = item.total_formatted;
                priceWrapper.appendChild(price);

                content.appendChild(priceWrapper);

                li.appendChild(content);

                itemWrapper.appendChild(li);
            });

            if (response.order.shipping !== 0) {
                let shipping = document.createElement('li');
                shipping.classList.add('minicart__extra');

                let label = document.createElement('span');
                label.classList.add('minicart__extra-label');
                label.innerHTML = CommerceConfig.i18n.shipping || 'Shipping';
                shipping.appendChild(label);

                let value = document.createElement('span');
                value.classList.add('minicart__extra-value');
                value.innerHTML = response.order.shipping_formatted;
                shipping.appendChild(value);

                itemWrapper.appendChild(shipping);
            }

            // Add taxes if mode is exclusive
            if (response.order.total_before_tax === response.order.total_ex_tax && response.order.tax !== 0) {
                let tax = document.createElement('li');
                tax.classList.add('minicart__extra');

                let label = document.createElement('span');
                label.classList.add('minicart__extra-label');
                label.innerHTML = CommerceConfig.i18n.tax || 'Tax Inclusive';
                tax.appendChild(label);

                let value = document.createElement('span');
                value.classList.add('minicart__extra-value');
                value.innerHTML = response.order.tax_formatted;
                tax.appendChild(value);

                itemWrapper.appendChild(tax);
            }
        }

        let minicart = document.querySelector('.minicart');
        if (minicart) {
            minicart.style.display = response.order.total_quantity > 0 ? 'initial' : 'none';

            let totalQuantity = minicart.querySelectorAll('.minicart__quantity'),
                totalValue = minicart.querySelectorAll('.minicart__total-value');

            if (totalQuantity) {
                totalQuantity.forEach((el) => {
                    el.innerText = response.order.total_quantity;
                });
            }
            if (totalValue) {
                totalValue.forEach((el) => {
                    el.innerHTML = response.order.total_formatted;
                });
            }
        }
    }


    let lastRequest = null;
    function _request(method, url, data, callback, cancelLast) {
        data = data || null;
        cancelLast = typeof cancelLast !== "undefined" ? cancelLast : true;

        if (cancelLast && lastRequest) {
            lastRequest.abort();
        }

        let request = new XMLHttpRequest();
        request.open(method, url, true);
        request.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        if (method === 'POST') {
            // request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        }

        request.onreadystatechange = function() {
            if (this.readyState !== 4) {
                return;
            }
            if (this.status >= 200 && this.status < 400) {
                let resp = JSON.parse(this.responseText);
                callback(resp);
            } else if (this.status > 0) { // 0 = cancelled
                console.error(this);
            }
        };

        request.send(data);
        lastRequest = request;
    }

    // Polyfill for closest()
    if (!Element.prototype.matches) {
        Element.prototype.matches = Element.prototype.msMatchesSelector ||
            Element.prototype.webkitMatchesSelector;
    }

    if (!Element.prototype.closest) {
        Element.prototype.closest = function(s) {
            var el = this;

            do {
                if (el.matches(s)) return el;
                el = el.parentElement || el.parentNode;
            } while (el !== null && el.nodeType === 1);
            return null;
        };
    }
})();
