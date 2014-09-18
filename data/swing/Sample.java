import java.awt.*;
import java.awt.event.*;
import javax.swing.event.*;  
import java.awt.geom.*;
import java.awt.image.*;
import javax.swing.*;

/*
�g��k���̂ł���ȒP�Ȃ��G�`���v���O����
�\���̈�̍������ɂ��Ċg��k������

���ƂȂ��Ă��錻��
�Q��g�債�Ă���A�X�N���[���o�[�ŉE���Ɉړ�
���̌�ɂQ�񑱂��Ċg�傷��Ɛ���Ɉړ����Ȃ��ł�

��������E���i�����Ƃ��E�A���̏ꍇ�͏����j�Ŋg�傷���
����Ɉړ����Ȃ��݂����ł�
*/

public class Sample extends JPanel implements ActionListener{
	int PWIDTH = 400 , PHEIGHT = 300;

	BufferedImage bi;
	Graphics2D bg;
	Canvas canvas;
	
	double zoom = 1.0;
	JButton zoomin , zoomout;
	JScrollPane spCanvas;

	Sample() {
		canvas = new Canvas( );
		bi = new BufferedImage(PWIDTH , PHEIGHT , BufferedImage.TYPE_INT_RGB);
		bg = (Graphics2D)bi.createGraphics( );
		bg.fillRect(0 , 0 , PWIDTH , PHEIGHT);
		bg.setColor(Color.black);
		canvas.setPreferredSize(new Dimension(PWIDTH,PHEIGHT));

		spCanvas = new JScrollPane(canvas);
		spCanvas.setPreferredSize(new Dimension(PWIDTH + 100 , PHEIGHT + 100));
		setLayout(new BorderLayout( ));
		add(spCanvas , BorderLayout.CENTER);

		JPanel cp = new JPanel();
		cp.add(new JLabel(""));

		zoomin = new JButton("Zoom in"); zoomin.addActionListener(this); cp.add(zoomin);
		zoomout = new JButton("Zoom out"); zoomout.addActionListener(this); cp.add(zoomout);

		JPanel cpp = new JPanel(new FlowLayout( ));
		cpp.add(cp);
		add(cpp , BorderLayout.NORTH);
	}

	public void zooming(double zoom) {
		double origzoom = this.zoom;
		this.zoom = zoom;
		Point p = spCanvas.getViewport( ).getViewPosition( ); //����̍��W
		canvas.setPreferredSize(new Dimension((int)( PWIDTH * zoom ) , (int)( PHEIGHT * zoom )));
		canvas.revalidate( );
		// ����̈ʒu���g��k�������傫���ł̈ʒu�ɂ��킹��
        //<ins>
        Point pt = new Point((int)( p.x * zoom / origzoom ) , (int)( p.y * zoom / origzoom ));
        canvas.scrollRectToVisible(new Rectangle(pt, spCanvas.getViewport().getSize()));
        //</ins>
        spCanvas.getViewport( ).setViewPosition(new Point((int)( p.x * zoom / origzoom ) , (int)( p.y * zoom / origzoom )));   //���Ɠ����_������̍��W�ɗ���悤�ɒ����B
    }

	public void actionPerformed(ActionEvent e) {
		Object orig = e.getSource( );
		if( orig == zoomin ) { zooming(zoom * 2); repaint( ); return; }
		if( orig == zoomout ) { zooming(zoom / 2); repaint( ); return; }
	}

	class Canvas extends JPanel implements MouseListener,MouseMotionListener {
		int x0 , y0;
		boolean isClicked = false;

		Canvas() {
			addMouseListener(this);
			addMouseMotionListener(this);
			setBackground(Color.GRAY);
		}
		protected void paintComponent(Graphics g) {
			super.paintComponent(g);
			g.drawImage(bi , 0 , 0 , (int)( PWIDTH * zoom ) , (int)( PHEIGHT * zoom ) , this);
		}

		public void mousePressed(MouseEvent e) {
			if( javax.swing.SwingUtilities.isLeftMouseButton(e) ) {
				// ���N���b�N���̏���
				isClicked = true;
				x0 = (int)( e.getX( ) / zoom );
				y0 = (int)( e.getY( ) / zoom );
			}
		}
		public void mouseReleased(MouseEvent e) {
			if( javax.swing.SwingUtilities.isLeftMouseButton(e) ) {
				// ���N���b�N���̏���
				isClicked = false;
			}
		}

		public void mouseMoved(MouseEvent e) {

		}
		public void mouseDragged(MouseEvent e) {
			if( isClicked ) {
				int x1 , y1;
				x1 = (int)( e.getX( ) / zoom );
				y1 = (int)( e.getY( ) / zoom );
				bg.setColor(Color.BLACK);
				bg.drawLine(x0 , y0 , x1 , y1);
				//System.out.println("x,y" + x0 + "," + y0 + "x11y" + x1 + "," + y1);
				x0 = x1;
				y0 = y1;
				repaint( );
			}
		}
		public void mouseEntered(MouseEvent e) { }
		public void mouseExited(MouseEvent e) { }
		public void mouseClicked(MouseEvent e) { }

	}

	public static void main(String args[]) {
		JFrame frame = new JFrame( );
		frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
		Sample h = new Sample( );
		frame.getContentPane( ).add(h , BorderLayout.CENTER);
		frame.pack( );
		frame.setVisible(true);
	}
}
