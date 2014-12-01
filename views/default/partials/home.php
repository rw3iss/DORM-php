<link rel="stylesheet" href="/css/home.css?<?php echo time(); ?>" />

    <div class="container" id="home" ng-controlle="HomeController">

      <div class="page-header">
        <div class="row">
          <div class="col-lg-6">
            <h1>MUDMaker</h1>
            <p class="lead">MUD layout tool and area creator</p>
          </div>
          <div class="col-lg-6" style="padding: 15px 15px 0 15px;">
            <div class="well sponsor">
              <a href="#/area" ng-click="startNewArea()" rel="nofollow">
                <span style="float: left; margin-right: 15px;">
                  <img src="/img/screenshot.png" width="200" height="135">
                </span>
              </a>
              <a href="#/area" ng-click="startNewArea()" rel="nofollow">
                <h4 style="margin-bottom: 0.4em;">MUDMaker v1.0</h4>
                <div class="clearfix">
                  <p>Create, organize, refine, and export your MUD areas.</p>
                </div>
              </a>
            </div>
          </div>
        </div>
      </div>

      <div class="bs-docs-section">

        <div class="row">
          <div class="col-lg-6">
          
              <button type="button" class="btn btn-primary btn-lg" ng-click="startNewArea()">Start a new area</button>
              <button type="button" class="btn btn-primary btn-lg" ng-click="loadArea()">Load your previously saved area: <span class="area-name">{{previousArea.name}}</span></button>

           </div>
        </div>
      </div>

      <footer>
        <div class="row">
          <div class="col-lg-12">

            <ul class="list-unstyled">
              <li><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&amp;hosted_button_id=F22JEM3Q78JC2">Donate</a></li>
            </ul>
            <p>Made by <a href="mailto:rw3iss@gmail.com">Ryan Weiss</a>.</p>
            <p>Bootstrap theme by <a href="mailto:thomas@bootswatch.com">Thomas Park</a>.</p>
          </div>
        </div>

      </footer>


    </div>