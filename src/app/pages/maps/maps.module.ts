import { NgModule } from '@angular/core';
import { AgmCoreModule } from '@agm/core';
import { LeafletModule } from '@asymmetrik/ngx-leaflet';
import { NgxEchartsModule } from 'ngx-echarts';

import { ThemeModule } from '../../@theme/theme.module';
import { MapsRoutingModule, routedComponents } from './maps-routing.module';

@NgModule({
  imports: [
    ThemeModule,
    AgmCoreModule.forRoot({apiKey: 'AIzaSyBKDHpLMlYopg4Q-PZ1AGA3mEUublVbOg8'}),
    LeafletModule.forRoot(),
    MapsRoutingModule,
    NgxEchartsModule,
  ],
  exports: [],
  declarations: [
    ...routedComponents,
  ],
})
export class MapsModule { }
