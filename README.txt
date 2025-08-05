=== SC Events ===
Contributors: Pedro Matias
Tags: events, event management, shortcode, custom post type, calendar, agenda
Requires at least: 5.8
Tested up to: 6.5
Stable tag: 2.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.txt

A simple and flexible plugin to create, manage, and display events using a shortcode or a dedicated archive page.

== Description ==

SC Events provides a simple and powerful way to create and display events on your WordPress site. It's designed to be lightweight and easy to use, giving you control over your event listings without unnecessary complexity.

**Core Features:**

*   **Custom Event Post Type:** Adds a dedicated "Events" menu to your WordPress admin area for easy management.
*   **Detailed Event Information:** Attach crucial information to each event, including:
    *   Start Date and Hour
    *   End Date and Hour
    *   Event Place or Location
    *   A URL for registration
    *   Contact information
*   **Event Categories:** Organize your events with custom categories (e.g., "Workshops", "Webinars", "Conferences").
*   **Powerful Shortcode:** Use the `[sc_events]` shortcode to display a clean, modern grid of event cards on any page, post, or widget area.
*   **Customizable Display:** The shortcode supports attributes to control how many events are shown and to filter them by category.
*   **Automatic Events Page:** All your upcoming events are automatically displayed on a dedicated archive page at `yoursite.com/events`.
*   **Custom CSS Panel:** Easily tweak the styles of your event cards and pages by adding your own CSS rules directly from the admin dashboard, no file editing required.

This plugin is perfect for small businesses, community groups, educational institutions, or anyone who needs to display a clean agenda of upcoming events.

== Installation ==

1.  **From your WordPress Dashboard (easiest):**
    *   Navigate to 'Plugins' > 'Add New'.
    *   Click 'Upload Plugin'.
    *   Upload the `sc-events.zip` file and click 'Install Now'.
    *   Activate the plugin through the 'Plugins' menu in WordPress.

2.  **Via FTP:**
    *   Unzip the `sc-events.zip` file.
    *   Upload the `sc-events` folder to the `/wp-content/plugins/` directory on your server.
    *   Activate the plugin through the 'Plugins' menu in WordPress.

3.  **IMPORTANT POST-ACTIVATION STEP:**
    *   After activating, go to **Settings > Permalinks** in your admin dashboard.
    *   You don't need to change anything, just click the **"Save Changes"** button once. This registers the new `/events/` URL with WordPress.

== How to Use the Plugin ==

**1. Creating an Event**

*   Navigate to the **Events** menu in your WordPress admin dashboard and click **"Add New"**.
*   Enter a **Title** for your event.
*   Use the main content editor to write a **brief introduction** or description of the event.
*   In the **"Event Details"** box, fill in the specific information:
    *   **Start Date and Hour:** When the event begins.
    *   **End Date and Hour:** When the event ends. (Leave empty for single-day events).
    *   **Place:** The location of the event (e.g., "Zoom", "Main Auditorium").
    *   **Registry (URL):** The full link to a registration page or form.
    *   **Contacts:** Contact info like a phone number or email address.
*   On the right sidebar, use the **"Event Categories"** box to assign the event to a category. You can create new categories here as well.
*   Click the **"Publish"** button to save your event.

**2. Displaying Your Events**

You have two primary ways to display your events to visitors:

*   **The Main Archive Page (Automatic):**
    *   Simply direct your visitors to `http://www.yoursite.com/events/`. This page will automatically show all of your upcoming events in a grid.

*   **Using the Shortcode (Flexible):**
    *   You can place your event grid on ANY page or post.
    *   Edit a page and add a "Shortcode" block.
    *   Use the `[sc_events]` shortcode.

**3. Customizing the Shortcode with Attributes**

The shortcode is powerful and can be customized with attributes.

*   **Basic Usage (shows the next 3 upcoming events):**
    `[sc_events]`

*   **The `limit` attribute (to control the number of events shown):**
    `[sc_events limit="5"]`

*   **The `category` attribute (to show events from a specific category):**
    *   First, you need the category's "slug". You can find this by going to **Events > Categories**. The slug is the URL-friendly version of the name.
    *   Example: `[sc_events category="workshops"]`

*   **Combining Attributes:**
    *   You can use multiple attributes at the same time.
    *   Example (shows the next 2 events from the "webinars" category):
        `[sc_events limit="2" category="webinars"]`

**4. Customizing the Style**

*   Navigate to **Events > Custom CSS**.
*   Enter any CSS rules you want to apply to the events display. This is a great way to change colors, font sizes, or spacing to match your theme perfectly.
*   Example: To change the background color of the date box on the event cards to blue, you could add:
    `.sc-events-card__date { background-color: #0073aa; }`
*   Click **"Save Changes"**. Your custom styles will now be loaded on the front end.

== Screenshots ==

1. The "All Events" admin screen showing a list of created events.
2. The "Add New Event" screen, highlighting the "Event Details" meta box and the "Event Categories" box.
3. The front-end display of the event cards (the archive page or a shortcode).
4. The front-end display of a single event detail page.
5. The "Custom CSS" admin page showing the CSS editor.

== Changelog ==

= 2.0.0 =
*   Initial release of the SC Events plugin.
*   Features: Event Custom Post Type, Event Categories, custom meta fields for event details.
*   Includes `[sc_events]` shortcode with `limit` and `category` attributes.
*   Provides templates for the event archive and single event pages.
*   Includes a "Custom CSS" panel for easy styling overrides.

== AVADA ==
In your WordPress Dashboard, go to Avada > Layouts.
Click "Add New" to create a new layout. Give it a name like "Single Event Layout".
In the Layout Conditions, set it to display on "Events" > "All Events". This tells Avada to use this layout for every single event post.
Design your layout. You will likely just have a single section with one column.
Inside that column, add a "Code Block" element (or a "Text Block" element).
Inside the element, type the single shortcode: [sc_event_details]
Publish the layout.