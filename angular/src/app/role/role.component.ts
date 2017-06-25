import { Component, OnInit, AfterViewChecked } from '@angular/core';

import { ApiService } from '../services/api.service'
import { Role } from './role.interface'

@Component({
  selector: 'app-role',
  templateUrl: './role.component.html',
  styleUrls: ['./role.component.css']
})
export class RoleComponent implements OnInit 
{
	private roles: Role[] = [];

  constructor(
    private apiService: ApiService
  ) { }

  ngOnInit() {
    this.apiService.get('roles').subscribe(
			data => this.roles = data.data
		);
  }

  onFocus(event) {
    event.target.select()
  }

}
