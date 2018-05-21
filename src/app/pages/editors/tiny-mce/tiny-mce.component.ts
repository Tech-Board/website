import { Component } from '@angular/core';

@Component({
  selector: 'ngx-tiny-mce-page',
  template: `
    <nb-card>
      <nb-card-header>
        CCM Matangazo
      </nb-card-header>
      <nb-card-body>
        <ngx-tiny-mce></ngx-tiny-mce>
		<button type="submit" class="btn btn-danger">Wasilisha tangazo</button>
      </nb-card-body>
    </nb-card>
  `,
})
export class TinyMCEComponent {
}
