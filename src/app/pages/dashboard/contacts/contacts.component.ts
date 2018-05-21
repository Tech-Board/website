import { Component, OnDestroy, OnInit } from '@angular/core';
import { NbThemeService, NbMediaBreakpoint, NbMediaBreakpointsService } from '@nebular/theme';

import { UserService } from '../../../@core/data/users.service';

@Component({
  selector: 'ngx-contacts',
  styleUrls: ['./contacts.component.scss'],
  templateUrl: './contacts.component.html',
})
export class ContactsComponent implements OnInit, OnDestroy {

  contacts: any[];
  recent: any[];
  breakpoint: NbMediaBreakpoint;
  breakpoints: any;
  themeSubscription: any;

  constructor(private userService: UserService,
              private themeService: NbThemeService,
              private breakpointService: NbMediaBreakpointsService) {

    this.breakpoints = this.breakpointService.getBreakpointsMap();
    this.themeSubscription = this.themeService.onMediaQueryChange()
      .subscribe(([oldValue, newValue]) => {
        this.breakpoint = newValue;
      });
  }

  ngOnInit() {

    this.userService.getUsers()
      .subscribe((users: any) => {
        this.contacts = [
          {user: users.nick, type: 'Mwenyekiti wa shina'},
          {user: users.eva, type: 'Katibu mwenezi'},
          {user: users.jack, type: 'Mwenyekiti wa mtaa'},
          {user: users.lee, type: 'Balozi wa nyumba kumi'},
          {user: users.alan, type: 'Mwenyekiti wa mkoa'},
          {user: users.kate, type: 'Katibu wa kata'},
        ];

        this.recent = [
          {user: users.alan, type: 'home', time: '9:12 pm'},
          {user: users.eva, type: 'home', time: '7:45 pm'},
          {user: users.nick, type: 'mobile', time: '5:29 pm'},
          {user: users.lee, type: 'mobile', time: '11:24 am'},
          {user: users.jack, type: 'mobile', time: '10:45 am'},
          {user: users.kate, type: 'work', time: '9:42 am'},
          {user: users.kate, type: 'work', time: '9:31 am'},
          {user: users.jack, type: 'mobile', time: '8:01 am'},
        ];
      });
  }

  ngOnDestroy() {
    this.themeSubscription.unsubscribe();
  }
}
