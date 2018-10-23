if (typeof $TauriPower == "undefined") {
    var $TauriPower = new function() {
        function z(aA, az) {
            var ay = document.createElement(aA);
            if (az) {
                D(ay, az)
            }
            return ay
        }

        function Y(ay, az) {
            return ay.appendChild(az)
        }

        function ar(az, aA, ay) {
            if (window.attachEvent) {
                az.attachEvent("on" + aA, ay)
            } else {
                az.addEventListener(aA, ay, false)
            }
        }

        function D(aA, ay) {
            for (var az in ay) {
                if (typeof ay[az] == "object") {
                    if (!aA[az]) {
                        aA[az] = {}
                    }
                    D(aA[az], ay[az])
                } else {
                    aA[az] = ay[az]
                }
            }
        }

        function aj(ay) {
            if (!ay) {
                ay = event
            }
            if (!ay._button) {
                ay._button = ay.which ? ay.which : ay.button;
                ay._target = ay.target ? ay.target : ay.srcElement
            }
            return ay
        }

        function aa() {
            var ay = 0,
                az = 0;
            if (typeof window.innerWidth == "number") {
                ay = window.innerWidth;
                az = window.innerHeight
            } else {
                if (document.documentElement && (document.documentElement.clientWidth || document.documentElement.clientHeight)) {
                    ay = document.documentElement.clientWidth;
                    az = document.documentElement.clientHeight
                } else {
                    if (document.body && (document.body.clientWidth || document.body.clientHeight)) {
                        ay = document.body.clientWidth;
                        az = document.body.clientHeight
                    }
                }
            }
            return {
                w: ay,
                h: az
            }
        }

        function y() {
            var ay = 0,
                az = 0;
            if (typeof(window.pageYOffset) == "number") {
                ay = window.pageXOffset;
                az = window.pageYOffset
            } else {
                if (document.body && (document.body.scrollLeft || document.body.scrollTop)) {
                    ay = document.body.scrollLeft;
                    az = document.body.scrollTop
                } else {
                    if (document.documentElement && (document.documentElement.scrollLeft || document.documentElement.scrollTop)) {
                        ay = document.documentElement.scrollLeft;
                        az = document.documentElement.scrollTop
                    }
                }
            }
            return {
                x: ay,
                y: az
            }
        }

        function w(aA) {
            var ay, aB;
            if (window.innerHeight) {
                ay = aA.pageX;
                aB = aA.pageY
            } else {
                var az = y();
                ay = aA.clientX + az.x;
                aB = aA.clientY + az.y
            }
            return {
                x: ay,
                y: aB
            }
        }

        function am(az) {
            var aA = {};
            for (var ay in az) {
                aA[az[ay]] = ay
            }
            return aA
        }

        function ag(ay) {
            var az = ag.L;
            return (az[ay] ? az[ay] : 0)
        }
        ag.L = {
            fr: 2,
            de: 3,
            es: 6,
            ru: 7,
            ptr: 25
        };

        function W(ay) {
            var az;
            if (W.L) {
                az = W.L
            } else {
                az = W.L = am(ag.L)
            }
            return (az[ay] ? az[ay] : "www")
        }

        function f(ay) {
            var az = f.L;
            return (az[ay] ? az[ay] : -1)
        }
        f.L = {
            npc: 1,
            object: 2,
            item: 3,
            itemset: 4,
            quest: 5,
            spell: 6,
            zone: 7,
            faction: 8,
            pet: 9,
            achievement: 10,
            profile: 100
        };

        function X(ay) {
            Y(q, z("script", {
                type: "text/javascript",
                src: ay
            }))
        }

        function E(aD, az, aC) {
            var aB = {
                12: 1.5,
                13: 12,
                14: 15,
                15: 5,
                16: 10,
                17: 10,
                18: 8,
                19: 14,
                20: 14,
                21: 14,
                22: 10,
                23: 10,
                24: 0,
                25: 0,
                26: 0,
                27: 0,
                28: 10,
                29: 10,
                30: 10,
                31: 10,
                32: 14,
                33: 0,
                34: 0,
                35: 25,
                36: 10,
                37: 2.5,
                44: 4.69512176513672
            };
            if (aD < 0) {
                aD = 1
            } else {
                if (aD > 80) {
                    aD = 80
                }
            }
            if ((az == 14 || az == 12 || az == 15) && aD < 34) {
                aD = 34
            }
            if (aC < 0) {
                aC = 0
            }
            var ay;
            if (aB[az] == null) {
                ay = 0
            } else {
                var aA;
                if (aD > 70) {
                    aA = (82 / 52) * Math.pow((131 / 63), ((aD - 70) / 10))
                } else {
                    if (aD > 60) {
                        aA = (82 / (262 - 3 * aD))
                    } else {
                        if (aD > 10) {
                            aA = ((aD - 8) / 52)
                        } else {
                            aA = 2 / 52
                        }
                    }
                }
                ay = aC / aB[az] / aA
            }
            return ay
        }
        var Z = {
                applyto: 3
            },
            F, m, T, h, g, P, O, q = document.getElementsByTagName("head")[0],
            S = {},
            G = {},
            ai = {},
            av = {},
            i = {},
            J = {},
            ad = {},
            ao, r, Q, V, U, an = 1,
            ap = 0,
            A = !!(window.attachEvent && !window.opera),
            a = navigator.userAgent.indexOf("MSIE 7.0") != -1,
            c = navigator.userAgent.indexOf("MSIE 6.0") != -1 && !a,
            at = {
                loading: "Loading...",
                noresponse: "No response from server :("
            },
            s = 0,
            M = 1,
            ah = 2,
            e = 3,
            H = 4,
            B = 1,
            u = 2,
            au = 3,
            al = 5,
            C = 6,
            ae = 10,
            R = 100,
            b = 15,
            l = 15,
            af = {
                1: [S, "npc", "NPC"],
                2: [G, "object", "Object"],
                3: [ai, "item", "Item"],
                5: [av, "quest", "Quest"],
                6: [i, "spell", "Spell"],
                10: [J, "achievement", "Achievement"],
                100: [ad, "profile", "Profile"]
            },
            ak = {
                0: "enus",
                2: "frfr",
                3: "dede",
                6: "eses",
                7: "ruru",
                25: "ptr"
            };

        function N() {
            Y(q, z("link", {
                type: "text/css",
                href:  URL_WEBSITE + "/css/power.css?v=3",
                rel: "stylesheet"
            }));
            if (A) {
                Y(q, z("link", {
                    type: "text/css",
                    href: URL_WEBSITE + "/css/power_ie.css",
                    rel: "stylesheet"
                }));
            }
            ar(document, "mouseover", n)
        }

        function p(ay) {
            var az = w(ay);
            P = az.x;
            O = az.y
        }

        function o(aI, aG) {
            if (aI.nodeName != "A" && aI.nodeName != "AREA") {
                return -2323
            }
            if (!aI.href.length) {
                return
            }
            var aD, aC, aA, az, aE = {};
            var ay = function(aJ, aL, aK) {
                if (aL == "buff" || aL == "sock") {
                    aE[aL] = true
                } else {
                    if (aL == "rand" || aL == "ench" || aL == "lvl" || aL == "c") {
                        aE[aL] = parseInt(aK)
                    } else {
                        if (aL == "gems" || aL == "pcs") {
                            aE[aL] = aK.split(":")
                        } else {
                            if (aL == "who" || aL == "domain") {
                                aE[aL] = aK
                            } else {
                                if (aL == "when") {
                                    aE[aL] = new Date(parseInt(aK))
                                }
                            }
                        }
                    }
                }
            };
            if (Z.applyto & 1) {
                aD = 1;
                aC = 2;
                aA = 3;
                az = aI.href.match(/^http:\/\/(.+?)?\.?shoot\.tauri\.hu\/\?(item|quest|spell|achievement|npc|object|profile)=([^&#]+)/);
                ap = 0
            }
            if (az == null && (Z.applyto & 2) && aI.rel) {
                aD = 0;
                aC = 1;
                aA = 2;
                az = aI.rel.match(/(item|quest|spell|achievement|npc|object|profile)=([0-9]+)/);
                if (!az) {
                    az = aI.rel.match(/(profile)=([^&#]+)/)
                }
                ap = 1
            }
            if (aI.rel) {
                aI.rel.replace(/([a-zA-Z]+)=?([a-zA-Z0-9:-]*)/g, ay);
                if (aE.gems && aE.gems.length > 0) {
                    var aF;
                    for (aF = Math.min(3, aE.gems.length - 1); aF >= 0; --aF) {
                        if (parseInt(aE.gems[aF])) {
                            break
                        }
                    }++aF;
                    if (aF == 0) {
                        delete aE.gems
                    } else {
                        if (aF < aE.gems.length) {
                            aE.gems = aE.gems.slice(0, aF)
                        }
                    }
                }
            }
            if (az) {
                var aH, aB = "www";
                if (aE.domain) {
                    aB = aE.domain
                } else {
                    if (aD && az[aD]) {
                        aB = az[aD]
                    }
                }
                aH = ag(aB);
                if (aB == "wotlk") {
                    aB = "www"
                }
                h = aB;
                if (!aI.onmousemove) {
                    aI.onmousemove = ac;
                    aI.onmouseout = L
                }
                p(aG);
                d(f(az[aC]), az[aA], aH, aE)
            }
        }

        function n(aA) {
            aA = aj(aA);
            var az = aA._target;
            var ay = 0;
            while (az != null && ay < 3 && o(az, aA) == -2323) {
                az = az.parentNode;
                ++ay
            }
        }

        function ac(ay) {
            ay = aj(ay);
            p(ay);
            ax()
        }

        function L() {
            F = null;
            I()
        }

        function aq() {
            if (!ao) {
                var aD = z("div"),
                    aH = z("table"),
                    az = z("tbody"),
                    aC = z("tr"),
                    aA = z("tr"),
                    ay = z("td"),
                    aG = z("th"),
                    aF = z("th"),
                    aE = z("th");
                aD.className = "wowhead-tooltip";
                aG.style.backgroundPosition = "top right";
                aF.style.backgroundPosition = "bottom left";
                aE.style.backgroundPosition = "bottom right";
                Y(aC, ay);
                Y(aC, aG);
                Y(az, aC);
                Y(aA, aF);
                Y(aA, aE);
                Y(az, aA);
                Y(aH, az);
                V = z("p");
                V.style.display = "none";
                Y(V, z("div"));
                Y(aD, V);
                Y(aD, aH);
                Y(document.body, aD);
                ao = aD;
                r = aH;
                Q = ay;
                var aB = z("div");
                aB.className = "wowhead-tooltip-powered";
                Y(aD, aB);
                U = aB;
                I()
            }
        }

        function j(aB, aC, itemID) {
            var aD = false;
            if (!ao) {
                aq()
            }
            if (!aB) {
                aB = af[F][2] + " not found :(";
                aC = "inv_misc_questionmark";
                aD = true
            } else {

                var armoryData = characterArmoryData;
                if ( armoryData && armoryData.response )
                {
                    var armoryItems = characterArmoryData.response.characterItems;
                    var armoryItem;
                    for ( var itemEntry = 0 ; itemEntry < armoryItems.length ; ++itemEntry )
                    {
                        if ( armoryItems[itemEntry].entry === itemID )
                        {
                            armoryItem = armoryItems[itemEntry];
                            break;
                        }
                    }

                    if ( armoryItem )
                    {

                    }
                }

                if (g != null) {
                    if (g.pcs && g.pcs.length) {
                        var aE = 0;
                        for (var aA = 0, az = g.pcs.length; aA < az; ++aA) {
                            var ay;
                            if (ay = aB.match(new RegExp("<span><!--si([0-9]+:)*" + g.pcs[aA] + "(:[0-9]+)*-->"))) {
                                aB = aB.replace(ay[0], '<span class="q8"><!--si' + g.pcs[aA] + "-->");
                                ++aE
                            }
                        }
                        if (aE > 0) {
                            aB = aB.replace("(0/", "(" + aE + "/");
                            aB = aB.replace(new RegExp("<span>\\(([0-" + aE + "])\\)", "g"), '<span class="q2">($1)')
                        }
                    }
                    if (g.c) {
                        aB = aB.replace(/<span class="c([0-9]+?)">(.+?)<\/span><br \/>/g, '<span class="c$1" style="display: none">$2</span>');
                        aB = aB.replace(new RegExp('<span class="c(' + g.c + ')" style="display: none">(.+?)</span>', "g"), '<span class="c$1">$2</span><br />')
                    }
                    if (g.lvl) {
                        aB = aB.replace(/\(<!--r([0-9]+):([0-9]+):([0-9]+)-->([0-9.%]+)(.+?)([0-9]+)\)/g, function(aI, aK, aJ, aH, aF, aM, aG) {
                            var aL = E(g.lvl, aJ, aH);
                            aL = (Math.round(aL * 100) / 100);
                            if (aJ != 12 && aJ != 37) {
                                aL += "%"
                            }
                            return "(<!--r" + g.lvl + ":" + aJ + ":" + aH + "-->" + aL + aM + g.lvl + ")"
                        })
                    }
                    if (g.who && g.when) {
                        aB = aB.replace("<table><tr><td><br />", '<table><tr><td><br /><span class="q2">' + sprintf(at.tooltip_achievementcomplete, g.who, g.when.getMonth() + 1, g.when.getDate(), g.when.getFullYear()) + "</span><br /><br />");
                        aB = aB.replace(/class="q0"/g, 'class="r3"')
                    }
                }
            }
            if (U) {
                U.style.display = (ap && !aD ? "" : "none")
            }
            if (an && aC) {
                V.style.backgroundImage = "url(https://wow.zamimg.com/images/wow/icons/medium/" + aC.toLowerCase() + ".jpg)";
                V.style.display = ""
            } else {
                V.style.backgroundImage = "none";
                V.style.display = "none"
            }
            ao.style.display = "";
            ao.style.width = "320px";
            Q.innerHTML = aB;
            aw();
            ax();
            ao.style.visibility = "visible"
        }

        function I() {
            if (!ao) {
                return
            }
            ao.style.display = "none";
            ao.style.visibility = "hidden"
        }

        function aw() {
            var az = Q.childNodes;
            if (az.length >= 2 && az[0].nodeName == "TABLE" && az[1].nodeName == "TABLE") {
                az[0].style.whiteSpace = "nowrap";
                var ay;
                if (az[1].offsetWidth > 300) {
                    ay = Math.max(300, az[0].offsetWidth) + 20
                } else {
                    ay = Math.max(az[0].offsetWidth, az[1].offsetWidth) + 20
                }
                if (ay > 20) {
                    ao.style.width = ay + "px";
                    az[0].style.width = az[1].style.width = "100%"
                }
            } else {
                ao.style.width = r.offsetWidth + "px"
            }
        }

        function ax() {
            if (!ao) {
                return
            }
            if (P == null) {
                return
            }
            var aH = aa(),
                aI = y(),
                aE = aH.w,
                aB = aH.h,
                aD = aI.x,
                aA = aI.y,
                aC = r.offsetWidth,
                ay = r.offsetHeight,
                az = P + b,
                aG = O - ay - l;
            if (az + b + aC + 4 >= aD + aE) {
                var aF = P - aC - b;
                if (aF >= 0) {
                    az = aF
                } else {
                    az = aD + aE - aC - b - 4
                }
            }
            if (aG < aA) {
                aG = O + l;
                if (aG + ay > aA + aB) {
                    aG = aA + aB - ay;
                    if (an) {
                        if (P >= az - 48 && P <= az && O >= aG - 4 && O <= aG + 48) {
                            aG -= 48 - (O - aG)
                        }
                    }
                }
            }
            ao.style.left = az + "px";
            ao.style.top = aG + "px"
        }

        function k(ay) {
            return (g && g.buff ? "buff_" : "tooltip_") + ak[ay]
        }

        function x(az, aB, aA) {
            var ay = af[az][0];
            if (ay[aB] == null) {
                ay[aB] = {}
            }
            if (ay[aB].status == null) {
                ay[aB].status = {}
            }
            if (ay[aB].status[aA] == null) {
                ay[aB].status[aA] = s
            }
        }

        function d(az, aD, aB, aC) {
            if (!aC) {
                aC = {}
            }
            var aA = K(aD, aC);
            F = az;
            m = aA;
            T = aB;
            g = aC;
            x(az, aA, aB);
            var ay = af[az][0];
            if (ay[aA].status[aB] == H || ay[aA].status[aB] == e) {
                j(ay[aA][k(aB)], ay[aA].icon, parseInt(aD))
            } else {
                if (ay[aA].status[aB] == M) {
                    j(at.tooltip_loading)
                } else {
                    ab(az, aD, aB, null, aC)
                }
            }
        }

        function ab(aG, aC, aH, aA, aD) {
            var ay = K(aC, aD);
            var aF = af[aG][0];
            if (aF[ay].status[aH] != s && aF[ay].status[aH] != ah) {
                return
            }
            aF[ay].status[aH] = M;
            if (!aA) {
                aF[ay].timer = setTimeout(function() {
                    v.apply(this, [aG, ay, aH])
                }, 333)
            }
            var aB = "";
            for (var aE in aD) {
                if (aE != "rand" && aE != "ench" && aE != "gems" && aE != "sock") {
                    continue
                }
                if (typeof aD[aE] == "object") {
                    aB += "&" + aE + "=" + aD[aE].join(":")
                } else {
                    if (aE == "sock") {
                        aB += "&sock"
                    } else {
                        aB += "&" + aE + "=" + aD[aE]
                    }
                }
            }
            var az = "";
            if (F == R) {
                az += "http://profiler.tauri.com"
            } else {
                az += URL_WEBSITE
            }
            az += "/tooltip?" + af[aG][1] + "=" + aC + "&power2" + aB;
            console.log(az);
            X(az)
        }

        function v(az, aB, aA) {
            if (F == az && m == aB && T == aA) {
                j(at.loading);
                var ay = af[az][0];
                ay[aB].timer = setTimeout(function() {
                    t.apply(this, [az, aB, aA])
                }, 3850)
            }
        }

        function t(az, aB, aA) {
            var ay = af[az][0];
            ay[aB].status[aA] = ah;
            if (F == az && m == aB && T == aA) {
                j(at.tooltip_noresponse)
            }
        }

        function K(az, ay) {
            return az + (ay.rand ? "r" + ay.rand : "") + (ay.ench ? "e" + ay.ench : "") + (ay.gems ? "g" + ay.gems.join(",") : "") + (ay.sock ? "s" : "")
        }
        this.register = function(aA, aC, aB, az) {
            var ay = af[aA][0];
            clearTimeout(ay[aC].timer);
            D(ay[aC], az);
            if (ay[aC][k(aB)]) {
                ay[aC].status[aB] = H
            } else {
                ay[aC].status[aB] = e
            }
            if (F == aA && aC == m && T == aB) {
                j(ay[aC][k(aB)], ay[aC].icon, aC)
            }
        };
        this.registerNpc = function(aA, az, ay) {
            this.register(B, aA, az, ay)
        };
        this.registerObject = function(aA, az, ay) {
            this.register(u, aA, az, ay)
        };
        this.registerItem = function(aA, az, ay) {
            this.register(au, aA, az, ay)
        };
        this.registerQuest = function(aA, az, ay) {
            this.register(al, aA, az, ay)
        };
        this.registerSpell = function(aA, az, ay) {
            this.register(C, aA, az, ay)
        };
        this.registerAchievement = function(aA, az, ay) {
            this.register(ae, aA, az, ay)
        };
        this.registerProfile = function(aA, az, ay) {
            this.register(R, aA, az, ay)
        };
        this.set = function(ay) {
            D(Z, ay)
        };
        this.showTooltip = function(aA, ay, az) {
            p(aA);
            j(ay, az)
        };
        this.hideTooltip = function() {
            I()
        };
        this.moveTooltip = function(ay) {
            ac(ay)
        };
        setTimeout(function() {
            N()
        },1000);
    }
};