/* eslint-disable import/no-unresolved */
/**
 * Return if user is logged in
 * This is completely up to you and how you want to store the token in your frontend application
 * e.g. If you are using cookies to store the application please update this function
 */

import { useAuthStore } from "@/plugins/auth.module"

export const isUserLoggedIn = () => !!(useAuthStore()._authenticated )


