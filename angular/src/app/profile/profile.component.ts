import { Component, OnInit, AfterViewChecked } from '@angular/core';
import { AuthService } from '../services/auth.service'
import { UserService } from '../services/user.service'

@Component({
  selector: 'app-profile',
  templateUrl: './profile.component.html',
  styleUrls: ['./profile.component.css']
})
export class ProfileComponent implements OnInit {
    user : any = null
    isUserSet = false
    editingName = false
    editName = ''

  constructor(
    private authService: AuthService,
    private userService: UserService
  ) {

  }

  ngOnInit() {
    [this.user, this.isUserSet] = [this.authService.getUser(), true]
  }

  onEditName() {
    this.editingName = true
    this.editName = this.user.name
  }

  onCancelEditName() {
    this.editingName = false
    this.editName = this.user.name
  }

  ngAfterViewChecked() {
    if (this.editingName) {
      let el = document.getElementById('inputName')
      el.focus()
    }
  }

  onSaveName() {
    this.userService.updateName(this.editName).subscribe(
      (response) => {
        this.user = response.user
        this.editingName = false
        this.editName = ''
        this.authService.refreshUser(this.user)
      }
    )
  }

  onFocus(event) {
    event.target.select()
  }

  getEmail() {
    this.user.email
  }

  getName() {
    this.user.name
  }
}
