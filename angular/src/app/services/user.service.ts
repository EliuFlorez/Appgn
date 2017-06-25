import { Injectable } from '@angular/core'
import { Http , Headers, Response } from '@angular/http'
import { Observable } from 'rxjs';
import 'rxjs/Rx';
import { tokenNotExpired } from 'angular2-jwt';
import { AuthService } from './auth.service'

@Injectable()
export class UserService {

  constructor(
    private http: Http,
    private authService: AuthService
  ) { }

  updateName(name) {
    const body = {
      name: name
    }
    const headers = new Headers()
    headers.append('X-Requested-With', 'XMLHttpRequest');
    const token = this.authService.getAuthToken()
    const user = this.authService.getUser()
    const baseURL = this.authService.getBaseUrl()

    return this.http.put(baseURL + '/api/user/' + user.id + '?token=' + token,
      body,
      { headers: headers }
    ).map(res => res.json());
  }

}
