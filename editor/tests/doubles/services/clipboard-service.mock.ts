export type ClipboardServiceMock = {
  copyText: jest.Mock<Promise<void>, [text: string]>;
  copyBlob: jest.Mock<Promise<void>, [blob: Blob]>;
};

export const makeClipboardService = (): ClipboardServiceMock => ({
  copyText: jest.fn().mockReturnValue(Promise.resolve()),
  copyBlob: jest.fn().mockReturnValue(Promise.resolve()),
});
