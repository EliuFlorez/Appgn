import { Injectable } from '@angular/core'
import { Http , Headers, Response } from '@angular/http'
import { Observable } from 'rxjs'
import 'rxjs/Rx'
import { Subject } from 'rxjs/Subject'
import { tokenNotExpired } from 'angular2-jwt';

@Injectable()
export class AuthService {
	
  authToken: any;
  user: any;
  baseURL: string = 'http://localhost:8000';
  private userSource = new Subject()

  userRefreshed$ = this.userSource.asObservable()

  constructor(private http: Http) { }

  signup(name: string, email: string, password: string) {
    const body = {
      name: name,
      email: email,
      password: password
    };
    const headers = new Headers()
    headers.append('X-Requested-With', 'XMLHttpRequest');

    return this.http.post(this.baseURL + '/api/user/signup',
      body,
      { headers: headers }
    ).map(res => res.json());
  }

  login(email: string, password: string){
    const body = {
      email: email,
      password: password
    };
    const headers = new Headers()
    headers.append('X-Requested-With', 'XMLHttpRequest');

    this.authToken = this.http.post(this.baseURL + '/api/user/signin',
      body,
      { headers: headers }
    ).map((response: Response) => {
      return {token: response.json().token}
    })
    .do(tokenData => localStorage.setItem('id_token', tokenData.token));
    return this.authToken;
  }

  /*

  */
  getAuthenticatedUser() {
    const headers = new Headers()

    headers.append('X-Requested-With', 'XMLHttpRequest');

    this.user = this.http.get(this.baseURL + '/api/user?token=' + this.getAuthToken(),
      { headers: headers }
    ).map(res => {
      return { user: res.json().user }
    })
    .do(userData => {
      localStorage.setItem('user', JSON.stringify(userData.user))
    })
    return this.user;
  }

  storeUserData(token, user) {
    localStorage.setItem('token', token);
    localStorage.setItem('user', JSON.stringify(user));
    this.authToken = token;
    this.user = user;
  }

  getAuthToken() {
    return localStorage.getItem('id_token');
  }

  getUser() {
    return JSON.parse(localStorage.getItem('user'));
  }

  setAuthToken(token) {
    this.authToken = token;
  }

  setuser(user) {
    localStorage.setItem('user', JSON.stringify(user));
  }

  loggedIn() {
    return tokenNotExpired('id_token');
  }

  logout() {
    this.authToken = null;
    this.user = null;
    localStorage.clear();
  }

  getBaseUrl() {
    return this.baseURL
  }

  refreshUser(user) {
    this.setuser(user)
    this.userSource.next(user)
  }

}
