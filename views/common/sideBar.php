<div class="sidebar d-flex flex-column">
    <div class="mb-4">
        <h5 class="fw-bold">JB JEWELLERS</h5>
    </div>
    <div class="d-flex flex-column justify-content-between h-100">

        <div class="sidebar_list">
            <div ng-repeat="navlist in jb.navList">
                <h6 class="text-muted small mt-4 text-uppercase">{{navlist.title}}</h6>
                <ul class="list-inline">
                    <li class="nav-link mb-2 text-capitalize" ng-class="{'active': jb.currentPath === list.link}"
                        ng-repeat="list in navlist.list">
                        <a class="text-decoration-none text-muted" href="{{list.link}}">{{list.linkName}}</a>
                    </li>
                </ul>

            </div>
        </div>
        <div>
            <a href="logout" class="btn btn-danger w-100">Logout</a>
        </div>
    </div>
</div>