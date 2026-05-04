export type ToastServiceMock = {
  topRight: jest.Mock;
  topCenter: jest.Mock;
  bottomRight: jest.Mock;
  bottomCenter: jest.Mock;
  success: jest.Mock;
  info: jest.Mock;
  warn: jest.Mock;
  error: jest.Mock;
  add: jest.Mock;
};

export const makeToastService = (): ToastServiceMock => ({
  topRight: jest.fn().mockReturnThis(),
  topCenter: jest.fn().mockReturnThis(),
  bottomRight: jest.fn().mockReturnThis(),
  bottomCenter: jest.fn().mockReturnThis(),
  success: jest.fn().mockReturnThis(),
  info: jest.fn().mockReturnThis(),
  warn: jest.fn().mockReturnThis(),
  error: jest.fn().mockReturnThis(),
  add: jest.fn().mockReturnThis(),
});
