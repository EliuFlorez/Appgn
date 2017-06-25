import { Component, OnInit, DoCheck, OnChanges, Input } from '@angular/core';
import { AuthService } from '../services/auth.service'

@Component({
  selector: 'app-navbar',
  templateUrl: './navbar.component.html',
  styleUrls: ['./navbar.component.css']
})
export class NavbarComponent implements OnInit {
  user: any;
  name: string;
  counterValue: number = 0;

  constructor(private authService:AuthService) {
    authService.userRefreshed$.subscribe(
      (user) => this.user = user
    )
  }

  ngOnInit() {

  }

  ngDoCheck() {
    if (!!!this.user && this.authService.loggedIn()) {
      this.user = this.authService.getAuthenticatedUser()
        .subscribe(response => {
          this.user = response.user
        });
    }
  }
  
  logout() {
    this.authService.logout();
  }



}
