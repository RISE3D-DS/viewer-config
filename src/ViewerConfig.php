<?php

namespace Rise3d\ViewerConfig;

class ViewerConfig
{
    protected $permissions;

    /**
     * Features that can be turned on and off in the HxDR component.
     * Per feature assign if it uses the show, create update (true)
     * functionality or if it's just enabled / disabled (false).
     *
     * @var array
     */
    protected $features = [
        'constructionArea' => 'object', // enables construction area feature
        'phases' => 'object', // enables phases feature
        'annotations' => 'object', // enables annotation feature
        'phaseObjects' => 'object', // enables option to place traffic and railroad controllers
        'trafficPlan' => 'object', // enables traffic plan upload feature
        'measurements' => 'boolean', // enables measurements feature
        'slicing' => 'boolean', // enables slicing feature
        'crossSection' => 'boolean', // enables cross section feature
        'navigationControls' => 'boolean', // enables all the controls at the bottom right
        'switch2d' => 'boolean', // enables the “switch to 2D” button
        'searchBar' => 'boolean', // enables the PDOK search bar
        'layers' => 'boolean', // enables the layer list button
        'settings' => 'boolean', // enables the settings button (which does nothing yet)
        'upload' => 'boolean', // enables the upload button (which does nothing yet)
        'swiping' => 'boolean', // enables the layer comparison tool
        'threeD' => 'boolean', // initialize the map in 2d or 3d mode, also available via a query parameter
        'overviewMapVisible' => 'boolean', // enables an overview map
        'overviewMapCollapsedInitially' => 'boolean', // enables an overview map
        'sunStudy' => 'boolean', // enables the sun study feature
        'los' => 'boolean', // enables the line of sight feature
        'tours' => 'boolean', // enables the tours feature
    ];

    /**
     * Actions per feature.
     *
     * @var array
     */
    protected $actions = [
        'show',
        'create',
        'update',
    ];

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->permissions = $this->setDefaultPermissions();
    }

    /**
     * Return an object of permissions.
     *
     * @return  object
     */
    public function permissions()
    {
        return json_encode($this->permissions);
    }

    /**
     * Set default permissions.
     * Default permission is always set to read-only mode.
     *
     * @return  array
     */
    protected function setDefaultPermissions()
    {
        $permissions = [
            'features' => [],
        ];

        // Add features.
        foreach ($this->features as $feature => $type) {
            // When feature should return a boolean.
            if ($type == 'boolean') {
                $permissions['features'][$feature] = false;
            }

            // When feature should return an object.
            if ($type == 'object') {
                $permissions['features'][$feature] = [];

                // Set default permissions.
                foreach ($this->actions as $action) {
                    $permissions['features'][$feature][$action] = false;
                }
            }
        }

        return $permissions;
    }

    /**
     * Grant permissions for all available features and actions.
     *
     * @return void
     */
    public function allowAll()
    {
        // Add features.
        foreach ($this->features as $feature => $type) {
            // When feature should return a boolean.
            if ($type == 'boolean') {
                $this->permissions['features'][$feature] = true;
            }

            // When feature should return an object.
            if ($type == 'object') {
                $this->permissions['features'][$feature] = [];

                // Set default permissions.
                foreach ($this->actions as $action) {
                    $this->permissions['features'][$feature][$action] = true;
                }
            }
        }

        return $this;
    }

    /**
     * Iterate given permissions and set grants.
     *
     * @param  string  $data
     * @return void
     */
    public function allow(...$data)
    {
        foreach ($data as $feature) {
            // Split dot notation.
            $featureData = explode('.', $feature);

            // When a top level permission is given.
            if (count($featureData) == 1) {
                // When feature should return a boolean.
                if ($this->features[$featureData[0]] == 'boolean') {
                    $this->permissions['features'][$featureData[0]] = true;
                }

                // When feature should return an object.
                if ($this->features[$featureData[0]] == 'object') {
                    foreach ($this->actions as $action) {
                        $permission = [];
                        $permission[] = $featureData[0];
                        $permission[] = $action;

                        $this->grantPermission($permission);
                    }
                }
            }

            // When a sub level permission is given.
            if (count($featureData) > 1) {
                $this->grantPermission($featureData);
            }
        }

        return $this;
    }

    /**
     * Grant permission.
     *
     * @return void
     */
    public function grantPermission(array $permission)
    {
        $this->permissions['features'][$permission[0]][$permission[1]] = true;
    }

    /**
     * Convert permissions to JSON.
     *
     * @return string
     */
    public function toJson()
    {
        $data['viewerConfigResponse'] = get_object_vars($this)['permissions'];

        return json_encode($data);
    }
}
