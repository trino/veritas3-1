<!-- BEGIN STYLE CUSTOMIZER -->
<div class="theme-panel hidden-xs hidden-sm">
    <?php if (strlen($is_disabled) == 0 && $param != "view") {
      //  echo '<div class="toggler"></div>';//doesn't work in view mode, so remove it and be done with it
    }
    ?>
    <div class="toggler-close">
    </div>

    <div class="theme-options">
        <div class="theme-option theme-colors clearfix">
            <span>THEME COLOR</span>
            <ul>
                <li class="color-default <?php if ($settings->layout == 'default') echo "current"; ?> tooltips"
                    data-style="default" onclick="change_layout('default');" data-container="body"
                    data-original-title="Default">
                </li>
                <li class="color-darkblue tooltips <?php if ($settings->layout == 'darkblue') echo "current"; ?>"
                    data-style="darkblue" onclick="change_layout('darkblue');" data-container="body"
                    data-original-title="Dark Blue">
                </li>
                <li class="color-blue tooltips <?php if ($settings->layout == 'blue') echo "current"; ?>"
                    data-style="blue" onclick="change_layout('blue');" data-container="body" data-original-title="Blue">
                </li>
                <li class="color-grey tooltips <?php if ($settings->layout == 'grey') echo "current"; ?>"
                    data-style="grey" onclick="change_layout('grey');" data-container="body" data-original-title="Grey">
                </li>
                <li class="color-light tooltips <?php if ($settings->layout == 'light') echo "current"; ?>"
                    data-style="light" onclick="change_layout('light');" data-container="body"
                    data-original-title="Light">
                </li>
                <li class="color-light2 tooltips <?php if ($settings->layout == 'light2') echo "current"; ?>"
                    data-style="light2" onclick="change_layout('light2');" data-container="body" data-html="true"
                    data-original-title="Light 2">
                </li>
            </ul>
        </div>
        <div class="theme-option">
            <span>Theme Style</span>
            <select class="layout-style-option form-control input-sm">
                <option value="square" selected="selected">Square corners</option>
                <option value="rounded">Rounded  corners</option>
            </select>
        </div>
        <div class="theme-option">
            <span>Layout</span>
            <select class="layout-option form-control input-sm" onchange="change_box();" id="boxed">
                <option value="fluid" selected="selected">Fluid</option>
                <option value="boxed">Boxed</option>
            </select>
        </div>
        <div class="theme-option">
            <span>Header</span>
            <select class="page-header-option form-control input-sm" onchange="change_body();">
                <option value="fixed" selected="selected">Fixed</option>
                <option value="default">Default</option>
            </select>
        </div>
        <div class="theme-option">
            <span>Top Menu Dropdown</span>
            <select class="page-header-top-dropdown-style-option form-control input-sm" onchange="change_body();">
                <option value="light" selected="selected">Light</option>
                <option value="dark">Dark</option>
            </select>
        </div>
        <div class="theme-option">
            <span>Sidebar Mode</span>
            <select class="sidebar-option form-control input-sm" onchange="change_body();">
                <option value="fixed">Fixed</option>
                <option value="default" selected="selected">Default</option>
            </select>
        </div>
        <div class="theme-option">
            <span>Sidebar Menu</span>
            <select class="sidebar-menu-option form-control input-sm" onchange="change_body();">
                <option value="accordion" selected="selected">Accordion</option>
                <option value="hover">Hover</option>
            </select>
        </div>
        <div class="theme-option">
            <span>Sidebar Style</span>
            <select class="sidebar-style-option form-control input-sm" onchange="change_body();">
                <option value="default" selected="selected">Default</option>
                <option value="light">Light</option>
            </select>
        </div>
        <div class="theme-option">
            <span>Sidebar Position</span>
            <select class="sidebar-pos-option form-control input-sm" onchange="change_body();">
                <option value="left" selected="selected">Left</option>
                <option value="right">Right</option>
            </select>
        </div>
        <div class="theme-option">
            <span>theme_footer</span>
            <select class="page-footer-option form-control input-sm" onchange="change_body();">
                <option value="fixed">Fixed</option>
                <option value="default" selected="selected">Default</option>
            </select>
        </div>
    </div>
</div>