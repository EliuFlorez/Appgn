import { Component, OnInit } from '@angular/core';
import { NgForm } from "@angular/forms"
import { AuthService } from "../services/auth.service"
import { Router } from '@angular/router'

@Component({
  selector: 'app-signup',
  templateUrl: './signup.component.html',
  styleUrls: ['./signup.component.css']
})
export class SignupComponent implements OnInit {

  authToken: any;
  user: any;

  constructor(
    private authService: AuthService,
    private router: Router
  ) { }

  ngOnInit() {
  }

  onSignup(form: NgForm) {
    this.authService.signup(
      form.value.name, form.value.email, form.value.password)
      .subscribe(
        response => {
          //this.authService.storeUserData(data.token, data.user);
          this.router.navigate(['/login']);
        },
        error => console.log(error)
      );
  }

  storeUserData(token, user) {
    localStorage.setItem('id_token', token);
    localStorage.setItem('user', JSON.stringify(user));
    this.authToken = token;
    this.user = user;
  }

}
