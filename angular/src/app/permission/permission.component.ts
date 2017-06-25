import { Component, OnInit, AfterViewChecked } from '@angular/core';

import { ApiService } from '../services/api.service'
import { Permission } from './permission.interface'

@Component({
  selector: 'app-permission',
  templateUrl: './permission.component.html',
  styleUrls: ['./permission.component.css']
})
export class PermissionComponent implements OnInit 
{
	private permissions: Permission[] = [];

  constructor(
    private apiService: ApiService
  ) { }

  ngOnInit() {
    this.apiService.get('permissions').subscribe(
			data => this.permissions = data.data
		);
  }

  onFocus(event) {
    event.target.select()
  }

}
