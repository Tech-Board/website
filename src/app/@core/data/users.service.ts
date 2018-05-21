import { Injectable } from '@angular/core';
import { Observable } from 'rxjs/Observable';
import 'rxjs/add/observable/of';

let counter = 0;

@Injectable()
export class UserService {

  private users = {
    nick: { name: 'Daniel Magesa', picture: 'assets/images/danielm.png' },
    eva: { name: 'Amina Saidi', picture: 'assets/images/aminaSaidi.png' },
    jack: { name: 'John Mwakitalima', picture: 'assets/images/johnMwakitalima.png' },
    lee: { name: 'Idrisa Shelukindo', picture: 'assets/images/idrisaShelukindo.png' },
    alan: { name: 'Doris Mushi', picture: 'assets/images/dorisMushi.png' },
    kate: { name: 'Adam Mollel', picture: 'assets/images/adamMollel.png' },
  };

  private userArray: any[];

  constructor() {
    // this.userArray = Object.values(this.users);
  }

  getUsers(): Observable<any> {
    return Observable.of(this.users);
  }

  getUserArray(): Observable<any[]> {
    return Observable.of(this.userArray);
  }

  getUser(): Observable<any> {
    counter = (counter + 1) % this.userArray.length;
    return Observable.of(this.userArray[counter]);
  }
}
