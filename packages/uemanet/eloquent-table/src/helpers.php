<?php

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Config;

if (!function_exists('sortableUrlLink')) {
    /**
     * Returns a URL with supplied query parameters and current icon for
     * the current sort direction.
     *
     * @param string $title
     * @param array  $parameters
     *
     * @return string
     */
    function sortableUrlLink($title, $parameters)
    {
        $field = Request::input('field');
        $sort = strtolower(Request::input('sort'));

        // Make sure we flip sorting if the sort field is already descending
        if ($sort === 'desc') {
            $parameters['sort'] = 'asc';
        } else {
            $parameters['sort'] = 'desc';
        }

        /// Make sure the current html link is for the currently sorted field
        if ($field === $parameters['field']) {
            // Sort parameter will actually be the opposite of what is being displayed
            switch ($parameters['sort']) {
                case 'asc':
                    $icon = Config::get('eloquenttable.default_sorting_icons.desc_sort_class');
                    break;

                case 'desc';
                    $icon = Config::get('eloquenttable.default_sorting_icons.asc_sort_class');
                    break;
                default:
                    break;
            }
        } else {
            // Display the base sorting class icon
            $icon = sprintf('%s', Config::get('eloquenttable.default_sorting_icons.sort_class'));
        }

        // Merge the parameters with any get params
        $parameters = array_merge(Request::query(), $parameters);

        // Now we'll return a link of the current page with the sorting parameters attached
        return sprintf('<a class="link-sort" href="%s">%s <i class="%s"></i></a>', Request::url().'?'.http_build_query($parameters), $title, $icon);
    }
}
