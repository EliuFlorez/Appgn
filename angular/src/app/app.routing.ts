import { ModuleWithProviders } from "@angular/core"
import { Routes, RouterModule } from "@angular/router"

import { SignupComponent } from "./signup/signup.component"
import { AppComponent } from "./app.component"
import { DashboardComponent } from "./dashboard/dashboard.component"
import { LoginComponent } from "./login/login.component"
import { HomeComponent } from "./home/home.component"
import { ProfileComponent } from "./profile/profile.component"
import { RoleComponent } from "./role/role.component"
import { PermissionComponent } from "./permission/permission.component"
import { AuthGuard } from './guards/auth.guard'

const APP_ROUTES: Routes = [
  { path: '', component: HomeComponent },
  { path: 'signup', component: SignupComponent },
  { path: 'login', component: LoginComponent },
  { path: 'dashboard', component: DashboardComponent, canActivate: [AuthGuard] },
  { path: 'profile', component: ProfileComponent, canActivate: [AuthGuard] },
  { path: 'role', component: RoleComponent, canActivate: [AuthGuard] },
  { path: 'permission', component: PermissionComponent, canActivate: [AuthGuard] },
]

export const routing: ModuleWithProviders = RouterModule.forRoot(APP_ROUTES);
