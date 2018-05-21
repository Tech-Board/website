import { Component } from '@angular/core';

@Component({
  selector: 'ngx-security-cameras',
  styleUrls: ['./security-cameras.component.scss'],
  templateUrl: './security-cameras.component.html',
})
export class SecurityCamerasComponent {

  cameras: any[] = [{
    title: 'Kinondoni - Magomeni',
    source: 'assets/images/trafficJam001.jpg',
  }, {
    title: 'Ilala - Tazara fly over',
    source: 'assets/images/nyerereRoad001.jpg',
  }, {
    title: 'Ubungo - Barabara imeharibika',
    source: 'assets/images/badRoad001.jpg',
  }, {
    title: 'Temeke - Bomba limepasuka',
    source: 'assets/images/brokenPipe001.jpg',
  }];

  selectedCamera: any = this.cameras[0];

  userMenu = [{
    title: 'Profile',
  }, {
    title: 'Log out',
  }];

  isSingleView = false;

  selectCamera(camera: any) {
    this.selectedCamera = camera;
    this.isSingleView = true;
  }
}
