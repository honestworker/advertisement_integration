(function($){
    "use strict";
    var QueryString = function() {
            for (var a, b = {}, c = window.location.search.substring(1), d = c.split("&"), e = 0; e < d.length; e++)
                if (a = d[e].split("="), "undefined" == typeof b[a[0]]) b[a[0]] = decodeURIComponent(a[1]);
                else if ("string" == typeof b[a[0]]) {
                var f = [b[a[0]], decodeURIComponent(a[1])];
                b[a[0]] = f
            } else b[a[0]].push(decodeURIComponent(a[1]));
            return b
        }(),
        ic_valid_state_list = ["AK", "AL", "AR", "AZ", "CA", "CO", "CT", "DC", "DE", "FL", "GA", "HI", "IA", "ID", "IL", "IN", "KS", "KY", "LA", "MA", "MD", "ME", "MI", "MN", "MO", "MS", "MT", "NC", "ND", "NE", "NH", "NJ", "NM", "NV", "NY", "OH", "OK", "OR", "PA", "RI", "SC", "SD", "TN", "TX", "UT", "VA", "VI", "VT", "WA", "WI", "WV", "WY"],
        env = "undefined" != typeof test && "undefined" != typeof test.env ? test.env : "production",
        protocol = "undefined" != typeof test && "undefined" != typeof test.protocol ? test.protocol : document.location.protocol,
        JQ = void 0,
        baseURL = void 0,
        sub_id = void 0,
        pub_id = void 0,
        publisher_id = void 0,
        inc_src = void 0,
        adver_id = void 0,
        feeds_limit = void 0,
        lead_type = void 0,
        requiredParametersLoaded = !0,
        state = "",
        zip = "",
        extraData = void 0,
        token = "",
        cInsstate = void 0;
    "production" === env ? baseURL = "//www.insuranceclicks.com" : "development" === env ? baseURL = "//dev2.insuranceclicks.com" : "local" === env ? baseURL = "//www.insuranceclicks.local" : void 0;
    sub_id = "undefined" != typeof QueryString.sub_id && null != QueryString.sub_id ? QueryString.sub_id : "undefined" != typeof cInssub_id && null != cInssub_id ? cInssub_id : "", zip = "undefined" != typeof QueryString.zipcode && null != QueryString.zipcode ? QueryString.zipcode : "undefined" != typeof cInszip && null != cInszip ? cInszip : "", cInsstate = "undefined" != typeof QueryString.state && null != QueryString.state ? QueryString.state : "undefined" != typeof cInsstate && null != cInsstate ? cInsstate : "", pub_id = "undefined" != typeof QueryString.pub_id && null != QueryString.pub_id ? QueryString.pub_id : "undefined" != typeof cInspub_id && null != cInspub_id ? cInspub_id : "", publisher_id = "undefined" != typeof QueryString.publisher_id && null != QueryString.publisher_id ? QueryString.publisher_id : "undefined" != typeof cInspublisher_id && null != cInspublisher_id ? cInspublisher_id : "", inc_src = "undefined" != typeof QueryString.src && null != QueryString.src ? QueryString.src : "undefined" != typeof cInsadsource && null != cInsadsource ? cInsadsource : "", adver_id = "undefined" != typeof QueryString.adver_id && null != QueryString.adver_id ? QueryString.adver_id : "undefined" != typeof cInsadverid && null != cInsadverid ? cInsadverid : "", feeds_limit = "undefined" != typeof cInsmaxads && null != cInsmaxads ? cInsmaxads : "", lead_type = "undefined" != typeof cInsinsurancetype && null != cInsinsurancetype ? cInsinsurancetype : "", extraData = "undefined" != typeof cInsextraData && null != cInsextraData ? cInsextraData : "", "undefined" != typeof cInsstate && null != cInsstate && "" != cInsstate ? -1 === ic_valid_state_list.indexOf(cInsstate) ? (document.getElementById("iClickContainer").innerHTML = "<p>Invalid State!!!</p>", requiredParametersLoaded = !1) : state = cInsstate : "undefined" != typeof cInszip && null != cInszip && "" != cInszip ? zip = cInszip : "undefined" != typeof cToken && null != cToken && "" != cToken ? (token = cToken, requiredParametersLoaded = !0) : (document.getElementById("iClickContainer").innerHTML = "<p>Zip OR State Field is Necessary To Show Results!!!</p>", requiredParametersLoaded = !1);
    var loadCSS = function() {
            var a = document.location.protocol,
                b = document.createElement("link");
            b.href = baseURL + "/iClickListing/style.css", b.rel = "stylesheet", b.type = "text/css", document.getElementsByTagName("body")[0].appendChild(b)
        },
        loadScript = function(a, b) {
            var c = document.createElement("script");
            c.type = "text/javascript", c.readyState ? c.onreadystatechange = function() {
                ("loaded" == c.readyState || "complete" == c.readyState) && (c.onreadystatechange = null, b())
            } : c.onload = function() {
                b()
            }, c.src = a, document.getElementsByTagName("head")[0].appendChild(c)
        },
        adDescriptionTemplate = function(a) {
            var b = a.split("*");
            if (1 === b.length && -1 === a.indexOf("*")) return a;
            var c = "<ul>",
                d = !0,
                e = !1,
                f = void 0;
            try {
                for (var g, h, i = b[Symbol.iterator](); !(d = (g = i.next()).done); d = !0) h = g.value, c += "<li class=\"cIns-li\">" + h + "</li>"
            } catch (a) {
                e = !0, f = a
            } finally {
                try {
                    !d && i.return && i.return()
                } finally {
                    if (e) throw f
                }
            }
            return c + "</ul>"
        },
        adTemplate = function(a, b) {
            return "\n    <div class=\"cIns-listing-box\">\n        <a style=\"cursor: pointer; text-decoration: none;\" href=\"" + b + "/" + a.click_url + "\" target=\"_blank\" class=\"removeClick\"></a>\n        <div class=\"cIns-left-side\">\n            <div class=\"cIns-left-side_top\">\n                <div class=\"cIns-ribn-pic\">\n                    <div class=\"cIns-counter_left\">" + a.position + "</div>\n                </div>\n                <div class=\"cIns-m-div\">\n                    <div class=\"cIns-right-side\">\n                        <div class=\"cIns-right-div\">\n                            <div class=\"cIns-apply-img\">\n                                <img style=\"display:inline !important\" src=\"" + a.logo + "\" />\n                            </div>\n                            <div class=\"cIns-apy-btn\">\n                                <img src=\"" + b + "/iClickListing/images/free-btn.png\"></a>\n                            </div>\n                        </div>\n                    </div>\n                </div>\n                <div class=\"cIns-name_left\">\n                    <h1 class=\"cIns-h1\">" + a.title + "</h1>\n                </div>\n            </div>\n            <div class=\"cIns-left-side_bottom\">\n                <div class=\"cIns-left-side_bottom_data\">" + adDescriptionTemplate(a.description) + "</div>\n                <div class=\"cIns-call-btn\" style=\"" + (a.show_phone ? "" : "display: none;") + "\">\n                    <a id=\"cIns-click_to_view_" + a.position + "\" style=\"cursor:pointer;\" onclick=\"showPhone(" + a.id + ", " + a.position + ", " + a.position + ", 1, '" + a.fullstate + "', '" + a.campaign + "', '" + a.advertiser + "', false, " + a.zipcode + ", '" + a.phone + "', " + a.leadid + ", " + a.click_id + ")\">\n                        <img src=\"" + b + "/iClickListing/images/mobile.png\" />\n                        <span>Click For Phone Number</span>\n                    </a>\n                    <a id=\"cIns-show_number_" + a.position + "\" style=\"display:none;\" href=\"tel: " + a.phone + "\">\n                        <img src=\"" + b + "/iClickListing/images/mobile.png\" />\n                        <span class=\"cIns-phone_shown\">" + a.phone + "</span>\n                    </a>\n                </div>\n            </div>\n        </div>\n        <div class=\"cIns-right-side\">\n            <div class=\"cIns-hid-on-lg\">\n                <div class=\"cIns-right-div\">\n                    <div class=\"cIns-apply-img\">\n                        <img style=\"display:inline !important\" src=\"" + a.logo + "\">\n                    </div>\n                </div>\n                <div class=\"cIns-apy-btn\">\n                    <img src=\"" + b + "/iClickListing/images/free-btn.png\">\n                </div>\n            </div>\n            <div class=\"cIns-locater\">\n                <img class=\"cIns-img\" src=\"" + b + "/iClickListing/images/location.png\">\n                <p class=\"cIns-p\">" + a.location + "</p>\n            </div>\n        </div>\n    </div>"
        },
        renderFeed = function(a) {
            var b = a.zipcode;
            "undefined" != typeof b && "" != b && (zip = b);
            var c = {
                type: "multistep",
                src: inc_src,
                pub_id: pub_id,
                sub_id: sub_id,
                publisher_id: publisher_id,
                adver_id: adver_id,
                state: state,
                zip: zip,
                feeds_limit: feeds_limit,
                lead_type: lead_type,
                current_page_url: window.location.href
            };
            for (var d in "" != token && (c.token = token), a) c[d] = a[d];
            "production" !== env && (console.group("Parameters being sent"), console.log(c), console.log(JQ.param(c)), console.groupEnd()), JQ.ajax({
                type: "GET",
                url: baseURL + "/iClickListing/listingProcess.php",
                data: c,
                dataType: "json",
                crossDomain: !0
            }).done(function(a) {
                var b = $("#iClickContainer");
                125 == publisher_id && (b = $("#ClickContainer")), b.html("");
                var c = !0,
                    d = !1,
                    e = void 0;
                try {
                    for (var f, g, h = a[Symbol.iterator](); !(c = (f = h.next()).done); c = !0) g = f.value, b.append(adTemplate(g, baseURL))
                } catch (a) {
                    d = !0, e = a
                } finally {
                    try {
                        !c && h.return && h.return()
                    } finally {
                        if (d) throw e
                    }
                }
            }).fail(function(a) {
                console.log(a.statusText)
            })
        },
        decodeHtml = function(a) {
            var b = document.createElement("textarea");
            return b.innerHTML = a, b.value
        },
        showPhone = function(a, b, c, d, e, f, g, h, i, j, k, l) {
            JQ.ajax({
                type: "GET",
                url: baseURL + "/iClickListing/clickAddProcess.php?action=AddClicks",
                data: "type=multistep&list_id=" + a + "&position_id=" + b + "&state=" + e + "&isPhoneClick=" + d + "&sub_id=" + sub_id + "&pub_id=" + pub_id + "&publisher_id=" + publisher_id + "&src=" + inc_src + "&adver_id=" + g + "&camp_id=" + f + "&phone_view=" + h + "&lead_type=" + lead_type + "&lead_id=" + k + "&impression_id=" + l + "&zip=" + i,
                dataType: "jsonp",
                crossDomain: !0
            }).done(function() {
                JQ("#cIns-click_to_view_" + c).hide(), JQ("#cIns-show_number_" + c).show(), window.location = "tel:" + j
            }).fail(function(a) {
                console.log(a.statusText)
            })
        },
        scriptLoadHandler = function() {
            var a, b = !1;
            $ && (a = $, b = !0), JQ = jQuery.noConflict(), loadCSS(), JQ(document).ready(renderFeed(extraData)), b && ($ = a)
        };
    requiredParametersLoaded && function() {
            if ("undefined" == typeof jQuery) {
                var a = document.createElement("script");
                a.setAttribute("type", "text/javascript"), a.setAttribute("src", "//" + baseURL + "/cdn/jquery-3.3.1.min.js"), a.readyState ? a.onreadystatechange = function() {
                    ("complete" == (void 0).readyState || "loaded" == (void 0).readyState) && scriptLoadHandler()
                } : a.onload = scriptLoadHandler, (document.getElementsByTagName("head")[0] || document.documentElement).appendChild(a)
            } else scriptLoadHandler()
        }(),
        function(a, b, c, d, e) {
            a[d] = a[d] || [], a[d].push({
                "gtm.start": new Date().getTime(),
                event: "gtm.js"
            });
            var g = b.getElementsByTagName(c)[0],
                f = b.createElement(c),
                h = "dataLayer" == d ? "" : "&l=" + d;
            f.async = !0, f.src = "https://www.googletagmanager.com/gtm.js?id=" + e + h, g.parentNode.insertBefore(f, g)
        }(window, document, "script", "dataLayer", "GTM-5S2Z56");
    var noscript_tag = document.createElement("div");
    noscript_tag.innerHTML = "<noscript><iframe src='https://www.googletagmanager.com/ns.html?id=GTM-5S2Z56' height='0' width='0' style='display:none;visibility:hidden'></iframe></noscript>";
})(jQuery);