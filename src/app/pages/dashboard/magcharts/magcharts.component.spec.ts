import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { MagchartsComponent } from './magcharts.component';

describe('MagchartsComponent', () => {
  let component: MagchartsComponent;
  let fixture: ComponentFixture<MagchartsComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ MagchartsComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(MagchartsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
