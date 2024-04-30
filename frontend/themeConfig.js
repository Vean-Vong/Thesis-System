import { breakpointsVuetify } from "@vueuse/core";
import { VIcon } from "vuetify/components/VIcon";

// ❗ Logo SVG must be imported with ?raw suffix
import { defineThemeConfig } from "@core";
import { RouteTransitions, Skins } from "@core/enums";
import app_login_image from "@images/app_login_image.png";
import logo from "@images/logo.png";
import {
  AppContentLayoutNav,
  ContentWidth,
  FooterType,
  NavbarType,
} from "@layouts/enums";

export const { themeConfig, layoutConfig } = defineThemeConfig({
  app: {
    title: "មន្ទីរពហុព្យាបាល និង សម្ភព សុខភីរម្យ",
    logo: h("img", {
      src: logo,
      style:
        "line-height:0; color: rgb(var(--v-global-theme-primary)); width: 40px;",
    }),
    app_login_image: h("img", {
      src: app_login_image,
      style: "line-height:0; color: rgb(var(--v-global-theme-primary));",
    }),
    contentWidth: ContentWidth.Fluid,
    contentLayoutNav: AppContentLayoutNav.Vertical,
    overlayNavFromBreakpoint: breakpointsVuetify.md + 16,
    enableI18n: true,
    theme: "system",
    isRtl: false,
    skin: Skins.Bordered,
    routeTransition: RouteTransitions.Fade,
    iconRenderer: VIcon,
  },
  navbar: {
    type: NavbarType.Sticky,
    navbarBlur: true,
  },
  footer: { type: FooterType.Static },
  verticalNav: {
    isVerticalNavCollapsed: false,
    defaultNavItemIconProps: { icon: "tabler-star", size: 13 },
    isVerticalNavSemiDark: false,
  },
  horizontalNav: {
    type: "sticky",
    transition: "slide-y-reverse-transition",
  },
  icons: {
    chevronDown: { icon: "tabler-chevron-down" },
    chevronRight: { icon: "tabler-chevron-right", size: 18 },
    close: { icon: "tabler-x" },
    verticalNavPinned: { icon: "tabler-circle-dot" },
    verticalNavUnPinned: { icon: "tabler-circle" },
    sectionTitlePlaceholder: { icon: "tabler-separator" },
  },
});
