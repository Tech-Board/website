import { NgModule } from '@angular/core';
import { ThemeModule } from '../../@theme/theme.module';
import { HttpClientModule } from '@angular/common/http';


@NgModule({
  imports: [
	ThemeModule,
	HttpClientModule,
  ],
  exports:[],
  declarations: [
  ]
})
export class LiveModule { }



