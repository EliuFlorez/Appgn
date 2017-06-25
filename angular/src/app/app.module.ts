import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { HttpModule } from '@angular/http';

import { AppComponent } from './app.component';
import { LoginComponent } from './login/login.component';
import { SignupComponent } from './signup/signup.component';
import { DashboardComponent } from './dashboard/dashboard.component';

import { routing } from './app.routing';
import { NavbarComponent } from './navbar/navbar.component'
import { AuthService } from './services/auth.service'
import { UserService } from './services/user.service'
import { ApiService } from './services/api.service'
import { HomeComponent } from './home/home.component';
import { AuthGuard } from './guards/auth.guard';
import { ProfileComponent } from './profile/profile.component';
import { RoleComponent } from './role/role.component';
import { PermissionComponent } from './permission/permission.component';

@NgModule({
  declarations: [
    AppComponent,
    LoginComponent,
    SignupComponent,
    DashboardComponent,
    NavbarComponent,
    HomeComponent,
    ProfileComponent,
    RoleComponent,
    PermissionComponent,
  ],
  imports: [
    BrowserModule,
    FormsModule,
    HttpModule,
    routing
  ],
  providers: [AuthService, UserService, ApiService, AuthGuard],
  bootstrap: [AppComponent]
})
export class AppModule { }
