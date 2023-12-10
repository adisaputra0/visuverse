(() => {
    var modules = {
        414: () => {
            !(function ($) {
                const isotopeConfig = {
                    gridSelector: ".hcf-isotope-grid",
                    itemSelector: ".hcf-isotope-item",
                    grid: "",
                    initiated() {
                        const gridElement = document.querySelector(isotopeConfig.gridSelector);
                        if (null != gridElement) {
                            gridElement.classList.add("isotope-initiated");
                        }
                    },
                    init() {
                        const grids = $(isotopeConfig.gridSelector);
                        if (grids.length > 0) {
                            isotopeConfig.grid = grids.imagesLoaded(() => {
                                isotopeConfig.grid.isotope({
                                    itemSelector: isotopeConfig.itemSelector,
                                    transitionDuration: "0.8s",
                                });
                            });
                            isotopeConfig.initiated();
                        }
                    },
                };

                if (document.readyState === "loading") {
                    document.addEventListener("DOMContentLoaded", () => { });
                }

                window.addEventListener(
                    "load",
                    () => {
                        isotopeConfig.init();
                    },
                    false
                );
            })(jQuery);
        },
    };
    var installedModules = {};

    function __webpack_require__(moduleId) {
        var module = installedModules[moduleId];
        if (module !== undefined) {
            return module.exports;
        }
        var newModule = (installedModules[moduleId] = {
            exports: {},
        });
        modules[moduleId](newModule, newModule.exports, __webpack_require__);
        return newModule.exports;
    }

    __webpack_require__.n = (module) => {
        var getter =
            module && module.__esModule
                ? () => module.default
                : () => module;
        __webpack_require__.d(getter, { a: getter });
        return getter;
    };

    __webpack_require__.d = (exports, definition) => {
        for (var key in definition) {
            if (
                __webpack_require__.o(definition, key) &&
                !__webpack_require__.o(exports, key)
            ) {
                Object.defineProperty(exports, key, {
                    enumerable: true,
                    get: definition[key],
                });
            }
        }
    };

    __webpack_require__.o = (obj, prop) =>
        Object.prototype.hasOwnProperty.call(obj, prop);

    (() => {
        "use strict";
        __webpack_require__(414);
    })();
})();
