import { Injectable } from '@angular/core'
import { Http , Headers, Response } from '@angular/http'
import { Observable } from 'rxjs';
import 'rxjs/Rx';
import 'rxjs/add/operator/map';
import 'rxjs/add/operator/do';
import 'rxjs/add/operator/catch';

import { tokenNotExpired } from 'angular2-jwt';
import { AuthService } from './auth.service'

@Injectable()
export class ApiService {

	user: any;
	token: any;
	baseURL: string = 'http://localhost:8000/api';
	headers: Headers = new Headers;
	
  constructor(
    private http: Http,
    private authService: AuthService
  ) { 
		this.headers.append('Content-Type', 'application/x-www-form-urlencoded');
		this.headers.append('X-Requested-With', 'XMLHttpRequest');
		this.user = this.authService.getUser();
		this.token = this.authService.getAuthToken();
	}

  get(endpoint) 
	{
    return this.http.get(
			this.baseURL + '/'+ endpoint + '?token=' + this.token
		).map(res => res.json());
  }
	
	show(endpoint, id) 
	{
    return this.http.get(
			this.baseURL + '/'+ endpoint +'/' + id + '?token=' + this.token,
    ).map(res => res.json());
  }
	
	save(endpoint, params) 
	{
    const body = {params};
    
		return this.http.post(
			this.baseURL + '/'+ endpoint + '?token=' + this.token,
      body, { headers: this.headers }
    ).map(res => res.json());
  }
	
	update(endpoint, params, id) 
	{
    const body = {params};
    
		return this.http.put(
			this.baseURL + '/'+ endpoint +'/' + id + '?token=' + this.token,
      body, { headers: this.headers }
    ).map(res => res.json());
  }
	
	destroy(endpoint, id) 
	{
    return this.http.delete(
			this.baseURL + '/'+ endpoint +'/' + id + '?token=' + this.token,
    ).map(res => res.json());
  }

}
