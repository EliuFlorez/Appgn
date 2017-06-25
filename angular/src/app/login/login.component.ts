import { Component, OnInit, Output, EventEmitter } from '@angular/core';
import { NgForm } from "@angular/forms"
import { AuthService } from "../services/auth.service"
import { Router } from '@angular/router'

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent implements OnInit {
  @Output() loggedIn = new EventEmitter();

  constructor(
    private router: Router,
    private authService: AuthService
  ) { }

  ngOnInit() { }

  onLogin(form: NgForm) {
    this.authService.login(form.value.email, form.value.password)
      .subscribe(
				(tokenData) => {
					console.log('token:', tokenData);
					this.authService.setAuthToken(tokenData.token);
          this.loggedIn.emit(tokenData);
          this.router.navigate(['/dashboard']);
        },
        error => console.log(error)
      );
  }

}
