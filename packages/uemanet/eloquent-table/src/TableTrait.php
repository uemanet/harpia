<?php

namespace Uemanet\EloquentTable;

use Closure;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;

trait TableTrait
{
    /*
     * Stores the columns to display
     *
     * @var array
     */
    public $eloquentTableColumns = array();

    /*
     * Stores the columns to hide when using
     * responsive templates
     *
     * @var array
     */
    public $eloquentTableHiddenColumns = array();

    /*
     * Stores the column modifications
     *
     * @var array
     */
    public $eloquentTableModifications = array();

    /*
    * Stores rows modifications
    *
    * @var array
    */
    public $eloquentTableRowAttributesModifications = array();

    /*
     * Stores cells modifications
     *
     * @var array
     */
    public $eloquentTableCellAttributesModifications = array();

    /*
     * Stores attributes to display onto the table
     *
     * @var array
     */
    public $eloquentTableAttributes = array();

    /*
     * Stores column relationship meanings
     *
     * @var array
     */
    public $eloquentTableMeans = array();

    /*
     * Stores column names to apply sorting
     *
     * @var array
     */
    public $eloquentTableSort = array();

    /*
     * Enables / disables showing the pages on the table if the collection
     * is paginated
     *
     * @var bool
     */
    public $eloquentTablePages = false;

    /**
     * Assigns columns to display.
     *
     * @param array $columns
     *
     * @return $this
     */
    public function columns(array $columns = array())
    {
        $this->eloquentTableColumns = $columns;

        return $this;
    }

    /**
     * Assigns columns to hide for smartphone viewing
     * on responsive designed websites such as bootstrap.
     *
     * @param array $columns
     *
     * @return $this
     */
    public function hidden(array $columns = array())
    {
        $this->eloquentTableHiddenColumns = $columns;

        return $this;
    }

    /**
     * Enables pages to be shown on the view.
     *
     * @return $this
     */
    public function showPages()
    {
        $this->eloquentTablePages = true;

        return $this;
    }

    /**
     * Assigns attributes to display on the table.
     *
     * @param array $attributes
     *
     * @return $this
     */
    public function attributes(array $attributes = array())
    {
        $this->eloquentTableAttributes = $this->arrayToHtmlAttributes($attributes);

        return $this;
    }

    /**
     * Generates view for the table.
     *
     * @param string $view
     *
     * @return mixed
     */
    public function render($view = '')
    {
        // If no attributes have been set, we'll set them to the configuration defaults
        if (empty($this->eloquentTableAttributes)) {
            $attributes = Config::get('eloquenttable.default_table_attributes', []);

            $this->attributes($attributes);
        }

        /*
         * If a view isn't specified, we'll check the configuration
         * separator to see what laravel version we're using so the
         * correct blade tags are used.
         */
        if (!$view) {
            $view = 'eloquenttable::table';
        }

        return View::make($view, [
            'collection' => $this,
        ])->render();
    }

    /**
     * Stores modifications to columns.
     *
     * @param string  $column
     * @param Closure $closure
     *
     * @return $this
     */
    public function modify($column, Closure $closure)
    {
        $this->eloquentTableModifications[$column] = $closure;

        return $this;
    }

    /**
     * Stores modifications to cells.
     *
     * @param string  $column
     * @param Closure $closure
     *
     * @return $this
     */
    public function modifyCell($column, $closure)
    {
        $this->eloquentTableCellAttributesModifications[$column] = $closure;

        return $this;
    }

    /**
     * Stores modifications to rows.
     *
     * @param string  $name
     * @param Closure $closure
     *
     * @return $this
     */
    public function modifyRow($name, $closure)
    {
        $this->eloquentTableRowAttributesModifications[$name] = $closure;

        return $this;
    }

    /**
     * Retrieves cell attributes.
     *
     * @param string $column
     * @param Array  $record
     *
     * @return string
     */
    public function getCellAttributes($column, $record = null)
    {
        $attributes = array();
        if (array_key_exists($column, $this->eloquentTableCellAttributesModifications)) {
            $attributes = call_user_func($this->eloquentTableCellAttributesModifications[$column], $record);
            if (array_key_exists($column, $this->eloquentTableHiddenColumns)) {
                $attributes = array_merge($attributes, $this->eloquentTableHiddenColumns[$column]);
            } elseif (in_array($column, $this->eloquentTableHiddenColumns)) {
                /*
                 * No custom attributes found, using default config attributes
                 */
                $attributes = array_merge($attributes, Config::get('eloquenttable.default_hidden_column_attributes'));
            }

            return $this->arrayToHtmlAttributes($attributes);
        }

        return;
    }

    /**
     * Retrieves row attributes.
     *
     * @param Array $record
     *
     * @return string
     */
    public function getRowAttributes($record)
    {
        $attributes = array();
        foreach ($this->eloquentTableRowAttributesModifications as $closure) {
            $tmpAtrributes = call_user_func($closure, $record);
            if (is_array($tmpAtrributes)) {
                $attributes = array_merge($attributes, $tmpAtrributes);
            }
        }

        return $this->arrayToHtmlAttributes($attributes);
    }

    /**
     * Stores columns to sort in an array.
     *
     * @param array $columns
     *
     * @return $this
     */
    public function sortable($columns = array())
    {
        $this->eloquentTableSort = $columns;

        return $this;
    }

    /**
     * Tells the collection to use a different key (such as a relationship key)
     * rather than the one specified in the column.
     *
     * @param string $column
     * @param string $relation
     *
     * @return $this
     */
    public function means($column, $relation)
    {
        $this->eloquentTableMeans[$column] = $relation;

        return $this;
    }

    /**
     * Retrieves an eloquent relationships nested property
     * from a column.
     *
     * @param string $column
     *
     * @return mixed
     */
    public function getRelationshipProperty($column)
    {
        $attributes = explode('.', $column);

        $tmpStr = $this;

        foreach ($attributes as $attribute) {
            if ($attribute === end($attributes)) {
                if (is_object($tmpStr)) {
                    $tmpStr = $tmpStr->$attribute;
                }
            } else {
                $tmpStr = $this->$attribute;
            }
        }

        return $tmpStr;
    }

    /**
     * Retrieves an eloquent relationship object from a column.
     *
     * @param string $column
     *
     * @return mixed
     */
    public function getRelationshipObject($column)
    {
        $attributes = explode('.', $column);

        if (count($attributes) > 1) {
            $relationship = $attributes[count($attributes) - 2];
        } else {
            $relationship = $attributes[count($attributes) - 1];
        }

        return $this->$relationship;
    }

    /**
     * Retrieves hidden column attributes.
     *
     * @param string $column
     *
     * @return string
     */
    public function getHiddenColumnAttributes($column)
    {
        /*
         * Check if custom attributes are being set on hidden column
         */
        if (array_key_exists($column, $this->eloquentTableHiddenColumns)) {
            return $this->arrayToHtmlAttributes($this->eloquentTableHiddenColumns[$column]);
        } elseif (in_array($column, $this->eloquentTableHiddenColumns)) {
            /*
             * No custom attributes found, using default config attributes
             */
            return $this->arrayToHtmlAttributes(Config::get('eloquenttable.default_hidden_column_attributes'));
        }

        /*
         * Column wasn't found on the table
         */
        return;
    }

    /**
     * Allows all columns on the current database table to be sorted through
     * query scope.
     *
     * @param $query
     * @param string $field
     * @param string $sort
     *
     * @return mixed
     */
    public function scopeSort($query, $field = null, $sort = null)
    {
        /*
         * Make sure both the field and sort variables are present
         */
        if ($field && $sort) {
            /*
             * Retrieve all column names for the current model table
             */
            $columns = Schema::getColumnListing($this->getTable());

            /*
             * Make sure the field inputted is available on the current table
             */
            if (in_array($field, $columns)) {
                /*
                 * Make sure the sort input is equal to asc or desc
                 */
                if ($sort === 'asc' || $sort === 'desc') {
                    /*
                     * Return the query sorted
                     */
                    return $query->orderBy($field, $sort);
                }
            }
        }

        /*
         * Default order by created at field
         */
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Overrides the newCollection method from the model this extends from.
     *
     * @param array $models
     *
     * @return TableCollection
     */
    public function newCollection(array $models = array())
    {
        return new TableCollection($models);
    }

    /**
     * Converts an array of attributes to an html attribute string.
     *
     * @param array $attributes
     *
     * @return string
     */
    private function arrayToHtmlAttributes(array $attributes = [])
    {
        $attributeString = '';

        if (!empty($attributes)) {
            foreach ($attributes as $key => $value) {
                $attributeString .= ' '.$key."='".$value."'";
            }
        }

        return $attributeString;
    }
}