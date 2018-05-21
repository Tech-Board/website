import { Component, OnDestroy } from '@angular/core';
import { NbThemeService } from '@nebular/theme';

@Component({
  selector: 'ngx-chartjs-pie',
  template: `
    <chart type="pie" [data]="data" [options]="options"></chart>
  `,
})
export class ChartjsPieComponent implements OnDestroy {
  data: any;
  options: any;
  themeSubscription: any;

  constructor(private theme: NbThemeService) {
    this.themeSubscription = this.theme.getJsTheme().subscribe(config => {

      const colors: any = config.variables;
      const chartjs: any = config.variables.chartjs;

	  console.log(colors);
	  
      this.data = {
        labels: ['Dawasco', 'Tanesco', 'Jiji', 'Zimamoto', 'Polisi','Tanroads','Sumatra','Tra'],
        datasets: [{
          data: [300, 500, 100, 250, 150, 50 , 60, 74],
          backgroundColor: [colors.danger, colors.dangerLight, colors.successLight, colors.success, colors.fgHeading, colors.warning, colors.warningLight, colors.info],
        }],
      };

      this.options = {
        maintainAspectRatio: false,
        responsive: true,
        scales: {
          xAxes: [
            {
              display: false,
            },
          ],
          yAxes: [
            {
              display: false,
            },
          ],
        },
        legend: {
          labels: {
            fontColor: chartjs.textColor,
          },
        },
      };
    });
  }

  ngOnDestroy(): void {
    this.themeSubscription.unsubscribe();
  }
}
