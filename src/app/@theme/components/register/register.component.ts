import { Component, Inject } from '@angular/core';
import { Router } from '@angular/router';
import { NB_AUTH_OPTIONS, NbAuthSocialLink } from '@nebular/auth/auth.options';
import { getDeepFromObject } from '@nebular/auth/helpers';

import { NbAuthService } from '@nebular/auth/services/auth.service';
import { NbAuthResult } from '@nebular/auth/services/auth-result';
//import { NbAuthBlockComponent } from './auth-block/auth-block.component';

@Component({
  selector: 'nb-register',
  styleUrls: ['./register.component.scss'],
  template: `
    <nb-auth-block>
      <h2 class="title">Register / Sajili</h2>
      <form (ngSubmit)="register()" #form="ngForm">

        <div *ngIf="showMessages.error && errors && errors.length > 0 && !submitted"
             class="alert alert-danger" role="alert">
          <div><strong>Oh snap!</strong></div>
          <div *ngFor="let error of errors">{{ error }}</div>
        </div>
        <div *ngIf="showMessages.success && messages && messages.length > 0 && !submitted"
             class="alert alert-success" role="alert">
          <div><strong>Hooray!</strong></div>
          <div *ngFor="let message of messages">{{ message }}</div>
        </div>

		
		<!-- Agency Code --> 
        <div class="form-group">
          <label for="input-agencycode" class="sr-only">Agency code</label>
          <input name="agencycode" [(ngModel)]="user.agencycode" id="input-agencycode" #agencycode="ngModel"
                 class="form-control" placeholder="Agency code / Namba ya shirika"
                 [class.form-control-danger]="agencycode.invalid && agencycode.touched"
                 [required]="getConfigValue('forms.validation.agencycode.required')"
                 [minlength]="getConfigValue('forms.validation.agencycode.minLength')"
                 [maxlength]="getConfigValue('forms.validation.agencycode.maxLength')"
                 autofocus>
          <small class="form-text error" *ngIf="agencycode.invalid && agencycode.touched && agencycode.errors?.required">
            Agency code is required! / Tafadhali weka namba ya shirika
          </small>
          <small
            class="form-text error"
            *ngIf="agencycode.invalid && agencycode.touched && (agencycode.errors?.minlength || agencycode.errors?.maxlength)">
            First name should contains
            from {{getConfigValue('forms.validation.agencycode.minLength')}}
            to {{getConfigValue('forms.validation.agencycode.maxLength')}}
            characters
          </small>
        </div>
		
		
		
		<!-- First name --> 
        <div class="form-group">
          <label for="input-firstname" class="sr-only">First name</label>
          <input name="firstName" [(ngModel)]="user.firstName" id="input-firstname" #firstName="ngModel"
                 class="form-control" placeholder="First name / Jina la kwanza"
                 [class.form-control-danger]="firstName.invalid && firstName.touched"
                 [required]="getConfigValue('forms.validation.firstName.required')"
                 [minlength]="getConfigValue('forms.validation.firstName.minLength')"
                 [maxlength]="getConfigValue('forms.validation.firstName.maxLength')"
                 autofocus>
          <small class="form-text error" *ngIf="firstName.invalid && firstName.touched && firstName.errors?.required">
            First name is required! / Tafadhali weka jina la kwanza
          </small>
          <small
            class="form-text error"
            *ngIf="firstName.invalid && firstName.touched && (firstName.errors?.minlength || firstName.errors?.maxlength)">
            First name should contains
            from {{getConfigValue('forms.validation.firstName.minLength')}}
            to {{getConfigValue('forms.validation.firstName.maxLength')}}
            characters
          </small>
        </div>
			
		
		<!-- Last name --> 
        <div class="form-group">
          <label for="input-lastname" class="sr-only">Last name</label>
          <input name="lastName" [(ngModel)]="user.lastName" id="input-lastname" #lastName="ngModel"
                 class="form-control" placeholder="Last name / Jina la Ukoo"
                 [class.form-control-danger]="lastName.invalid && lastName.touched"
                 [required]="getConfigValue('forms.validation.lastName.required')"
                 [minlength]="getConfigValue('forms.validation.lastName.minLength')"
                 [maxlength]="getConfigValue('forms.validation.lastName.maxLength')"
                 autofocus>
          <small class="form-text error" *ngIf="lastName.invalid && lastName.touched && lastName.errors?.required">
            Last name is required! / Tafadhali weka jina la ukoo
          </small>
          <small
            class="form-text error"
            *ngIf="lastName.invalid && lastName.touched && (lastName.errors?.minlength || lastName.errors?.maxlength)">
            First name should contains
            from {{getConfigValue('forms.validation.lastName.minLength')}}
            to {{getConfigValue('forms.validation.lastName.maxLength')}}
            characters
          </small>
        </div>

        <div class="form-group">
          <label for="input-email" class="sr-only">Email address</label>
          <input name="email" [(ngModel)]="user.email" id="input-email" #email="ngModel"
                 class="form-control" placeholder="Email address" pattern=".+@.+\..+"
                 [class.form-control-danger]="email.invalid && email.touched"
                 [required]="getConfigValue('forms.validation.email.required')">
          <small class="form-text error" *ngIf="email.invalid && email.touched && email.errors?.required">
            Email is required!
          </small>
          <small class="form-text error"
                 *ngIf="email.invalid && email.touched && email.errors?.pattern">
            Email should be the real one!
          </small>
        </div>

        <div class="form-group">
          <label for="input-password" class="sr-only">Password</label>
          <input name="password" [(ngModel)]="user.password" type="password" id="input-password"
                 class="form-control" placeholder="Password" #password="ngModel"
                 [class.form-control-danger]="password.invalid && password.touched"
                 [required]="getConfigValue('forms.validation.password.required')"
                 [minlength]="getConfigValue('forms.validation.password.minLength')"
                 [maxlength]="getConfigValue('forms.validation.password.maxLength')">
          <small class="form-text error" *ngIf="password.invalid && password.touched && password.errors?.required">
            Password is required!
          </small>
          <small
            class="form-text error"
            *ngIf="password.invalid && password.touched && (password.errors?.minlength || password.errors?.maxlength)">
            Password should contains
            from {{ getConfigValue('forms.validation.password.minLength') }}
            to {{ getConfigValue('forms.validation.password.maxLength') }}
            characters
          </small>
        </div>

        <div class="form-group">
          <label for="input-re-password" class="sr-only">Repeat password</label>
          <input
            name="rePass" [(ngModel)]="user.confirmPassword" type="password" id="input-re-password"
            class="form-control" placeholder="Confirm Password" #rePass="ngModel"
            [class.form-control-danger]="(rePass.invalid || password.value != rePass.value) && rePass.touched"
            [required]="getConfigValue('forms.validation.password.required')">
          <small class="form-text error"
                 *ngIf="rePass.invalid && rePass.touched && rePass.errors?.required">
            Password confirmation is required!
          </small>
          <small
            class="form-text error"
            *ngIf="rePass.touched && password.value != rePass.value && !rePass.errors?.required">
            Password does not match the confirm password.
          </small>
        </div>

        <div class="form-group accept-group col-sm-12" *ngIf="getConfigValue('forms.register.terms')">
          <nb-checkbox name="terms" [(ngModel)]="user.terms" [required]="getConfigValue('forms.register.terms')">
            Agree to <a href="#" target="_blank"><strong>Terms & Conditions / Sahihi ya mkataba</strong></a>
          </nb-checkbox>
        </div>

        <button [disabled]="submitted || !form.valid" class="btn btn-block btn-hero-success"
                [class.btn-pulse]="submitted">
          Register / Sajili
        </button>
      </form>

      <div class="links">

        <ng-container *ngIf="socialLinks && socialLinks.length > 0">
          <small class="form-text">Or connect with:</small>

          <div class="socials">
            <ng-container *ngFor="let socialLink of socialLinks">
              <a *ngIf="socialLink.link"
                 [routerLink]="socialLink.link"
                 [attr.target]="socialLink.target"
                 [attr.class]="socialLink.icon"
                 [class.with-icon]="socialLink.icon">{{ socialLink.title }}</a>
              <a *ngIf="socialLink.url"
                 [attr.href]="socialLink.url"
                 [attr.target]="socialLink.target"
                 [attr.class]="socialLink.icon"
                 [class.with-icon]="socialLink.icon">{{ socialLink.title }}</a>
            </ng-container>
          </div>
        </ng-container>

        <small class="form-text">
          Already have an account? (Tayari umesajili?) <a routerLink="../login"><strong>Sign in (Ingia)</strong></a>
        </small>
      </div>
    </nb-auth-block>
  `,
})
export class NbRegisterComponent {

  redirectDelay: number = 0;
  showMessages: any = {};
  provider: string = '';

  submitted = false;
  errors: string[] = [];
  messages: string[] = [];
  user: any = {};
  socialLinks: NbAuthSocialLink[] = [];

  constructor(protected service: NbAuthService,
              @Inject(NB_AUTH_OPTIONS) protected config = {},
              protected router: Router) {

    this.redirectDelay = this.getConfigValue('forms.register.redirectDelay');
    this.showMessages = this.getConfigValue('forms.register.showMessages');
    this.provider = this.getConfigValue('forms.register.provider');
    this.socialLinks = this.getConfigValue('forms.login.socialLinks');
  }

  register(): void {
    this.errors = this.messages = [];
    this.submitted = true;

    this.service.register(this.provider, this.user).subscribe((result: NbAuthResult) => {
      this.submitted = false;
      if (result.isSuccess()) {
        this.messages = result.getMessages();
      } else {
        this.errors = result.getErrors();
      }

      const redirect = result.getRedirect();
      if (redirect) {
        setTimeout(() => {
          return this.router.navigateByUrl(redirect);
        }, this.redirectDelay);
      }
    });
  }

  getConfigValue(key: string): any {
    return getDeepFromObject(this.config, key, null);
  }
}
