<nav class="side-menu">
    <ul class="side-menu-list">

            <li class="magenta opened"><a href="{{ route('dashboard') }}"> <i class="font-icon font-icon-dashboard"></i>  <span> Dashboard </span> </a></li>
            <li class="magenta"><a href="{{ route('categories.index') }}"> <i class="font-icon font-icon-page"></i>  <span>Contact lists </span> </a></li>
            <li class="magenta"><a href="{{ route('all-leads') }}"> <i class="font-icon font-icon-users"></i>  <span>Contacts </span> </a></li>

            <li class="magenta with-sub">
	            <span>
	                <i class="font-icon font-icon-user"></i>
	                <span class="lbl">Settings</span>
	            </span>
                <ul>
                     <li><a href="{{ route('settings.index') }}"><span class="lbl">Email</span></a></li>

                </ul>
            </li>

            <li class="magenta with-sub">
	            <span>
	                <i class="font-icon font-icon-user"></i>
	                <span class="lbl">Campaigns</span>
	            </span>
                <ul>
                     <li><a href="{{ route('campaign.create') }}"><span class="lbl">Create</span></a></li>
                     <li><a href="{{ route('campaign.index') }}"><span class="lbl">List</span></a></li>
                </ul>
            </li>


    </ul>

</nav>
