import { Injectable } from '@angular/core';

@Injectable()
export class ElectricityService {

  private data = [
    {
      title: '2015',
      months: [
        { month: 'Jan', delta: '0.97', down: true, amount: '816,000', cost: '97' },
        { month: 'Feb', delta: '1.83', down: true, amount: '806,000', cost: '95' },
        { month: 'Mar', delta: '0.64', down: true, amount: '803,000', cost: '94' },
        { month: 'Apr', delta: '2.17', down: false, amount: '818,000', cost: '98' },
        { month: 'May', delta: '1.32', down: true, amount: '809,000', cost: '96' },
        { month: 'Jun', delta: '0.05', down: true, amount: '808,000', cost: '96' },
        { month: 'Jul', delta: '1.39', down: false, amount: '815,000', cost: '97' },
        { month: 'Aug', delta: '0.73', down: true, amount: '807,000', cost: '95' },
        { month: 'Sept', delta: '2.61', down: true, amount: '792,000', cost: '92' },
        { month: 'Oct', delta: '0.16', down: true, amount: '791,000', cost: '92' },
        { month: 'Nov', delta: '1.71', down: true, amount: '786,000', cost: '89' },
        { month: 'Dec', delta: '0.37', down: false, amount: '789,000', cost: '91' },
      ],
    },
    {
      title: '2016',
      active: true,
      months: [
        { month: 'Jan', delta: '1.56', down: true, amount: '789,000', cost: '91' },
        { month: 'Feb', delta: '0.33', down: false, amount: '791,000', cost: '92' },
        { month: 'Mar', delta: '0.62', down: true, amount: '790,000', cost: '92' },
        { month: 'Apr', delta: '1.93', down: true, amount: '783,000', cost: '87' },
        { month: 'May', delta: '2.52', down: true, amount: '771,000', cost: '83' },
        { month: 'Jun', delta: '0.39', down: false, amount: '774,000', cost: '85' },
        { month: 'Jul', delta: '1.61', down: true, amount: '767,000', cost: '81' },
        { month: 'Aug', delta: '1.41', down: true, amount: '759,000', cost: '76' },
        { month: 'Sept', delta: '1.03', down: true, amount: '752,000', cost: '74' },
        { month: 'Oct', delta: '2.94', down: false, amount: '769,000', cost: '82' },
        { month: 'Nov', delta: '0.26', down: true, amount: '767,000', cost: '81' },
        { month: 'Dec', delta: '1.62', down: true, amount: '760,000', cost: '76' },
      ],
    },
    {
      title: '2017',
      months: [
        { month: 'Jan', delta: '1.34', down: false, amount: '789,000', cost: '91' },
        { month: 'Feb', delta: '0.95', down: false, amount: '793,000', cost: '93' },
        { month: 'Mar', delta: '0.25', down: true, amount: '791,000', cost: '92' },
        { month: 'Apr', delta: '1.72', down: false, amount: '797,000', cost: '95' },
        { month: 'May', delta: '2.62', down: true, amount: '786,000', cost: '90' },
        { month: 'Jun', delta: '0.72', down: false, amount: '789,000', cost: '91' },
        { month: 'Jul', delta: '0.78', down: true, amount: '784,000', cost: '89' },
        { month: 'Aug', delta: '0.36', down: true, amount: '782,000', cost: '88' },
        { month: 'Sept', delta: '0.55', down: false, amount: '787,000', cost: '90' },
        { month: 'Oct', delta: '1.81', down: true, amount: '779,000', cost: '86' },
        { month: 'Nov', delta: '1.12', down: true, amount: '774,000', cost: '84' },
        { month: 'Dec', delta: '0.52', down: false, amount: '776,000', cost: '95' },
      ],
    },
  ];

  constructor() {
  }

  // TODO: observables
  getData() {
    return this.data;
  }
}
