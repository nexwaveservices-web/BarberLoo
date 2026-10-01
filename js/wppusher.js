/**
 * BarberLoo - WP Pusher Client SDK & Realtime Webhook Bridge (js/wppusher.js)
 * 
 * Works both as:
 * 1. A standalone browser library (window.WPPusher) for frontends, WordPress embeds,
 *    and external sites.
 * 2. An ES module export for BarberLoo application components.
 */

import WPPusherDefault from '../wppusher.js';

export const WPPusher = WPPusherDefault;
export default WPPusher;
